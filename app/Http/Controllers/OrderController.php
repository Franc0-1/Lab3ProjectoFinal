<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['customer', 'items.pizza'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('delivery_method')) {
                $query->where('delivery_method', $request->delivery_method);
            }

            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            // Optimize date filtering using whereBetween for better performance
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('created_at', [
                    $request->date_from . ' 00:00:00',
                    $request->date_to . ' 23:59:59'
                ]);
            } elseif ($request->filled('date_from')) {
                $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
            } elseif ($request->filled('date_to')) {
                $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
            }

            $orders = $query->paginate(15);
            
            // Only load customers if needed (for filters dropdown)
            $customers = Customer::select('id', 'name', 'email')
                ->orderBy('name')
                ->get();

            return Inertia::render('Orders/Index', [
                'orders' => $orders,
                'customers' => $customers,
                'filters' => $request->only(['status', 'delivery_method', 'customer_id', 'date_from', 'date_to'])
            ]);
        } catch (Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage());
            return back()->withError('Error loading orders. Please try again.');
        }
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        try {
            $cart = Session::get('cart', []);
            
            if (empty($cart)) {
                return redirect()->route('welcome')->with('error', 'Tu carrito está vacío.');
            }
            
$pizzas = Pizza::with('category')->where('available', true)->orderBy('name')->get();
            
            $cartItems = [];
            $total = 0;
            
            foreach ($cart as $pizzaId => $quantity) {
                $pizza = $pizzas->firstWhere('id', $pizzaId);
                if ($pizza) {
                    $itemTotal = $pizza->price * $quantity;
                    $total += $itemTotal;
                    $cartItems[] = [
                        'pizza' => $pizza,
                        'quantity' => $quantity,
                        'total' => $itemTotal
                    ];
                }
            }
            
            return Inertia::render('Orders/Create', [
                'cartItems' => $cartItems,
                'total' => $total,
                'user' => Auth::user()
            ]);
        } catch (Exception $e) {
            Log::error('Error loading order creation form: ' . $e->getMessage());
            return back()->withError('Error loading form. Please try again.');
        }
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $validated = $request->validate([
                'delivery_method' => 'required|in:delivery,pickup',
                'payment_method' => 'required|in:cash,transfer',
                'delivery_address' => 'required_if:delivery_method,delivery|string|max:500',
                'phone' => 'required|string|max:50',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Get cart items
            $cart = Session::get('cart', []);
            if (empty($cart)) {
                return redirect()->route('welcome')->with('error', 'Tu carrito está vacío.');
            }

            // Get pizzas from database for cart items
            $pizzaIds = array_keys($cart);
            $pizzas = Pizza::whereIn('id', $pizzaIds)
                ->where('available', true)
                ->get()
                ->keyBy('id');

            // Calculate total
            $totalAmount = 0;
            $orderItems = [];

            foreach ($cart as $pizzaId => $quantity) {
                $pizza = $pizzas->get($pizzaId);
                if ($pizza) {
                    $itemTotal = $pizza->price * $quantity;
                    $totalAmount += $itemTotal;
                    $orderItems[] = [
                        'pizza_id' => $pizzaId,
                        'quantity' => $quantity,
                        'unit_price' => $pizza->price,
                        'pizza_name' => $pizza->name
                    ];
                }
            }

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['email' => Auth::user()->email],
                [
                    'name' => Auth::user()->name,
                    'phone' => $validated['phone'],
                    'address' => $validated['delivery_address'] ?? 'Retiro en local',
                    'frequent_customer' => false,
                ]
            );

            // Create the order
            $order = Order::create([
                'customer_id' => $customer->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'delivery_method' => $validated['delivery_method'],
                'payment_method' => $validated['payment_method'],
                'delivery_address' => $validated['delivery_address'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'],
                'delivery_date' => now()->addMinutes(30), // Estimated delivery
            ]);

            // Create order items using batch insert for better performance
            $orderItemsToInsert = [];
            foreach ($orderItems as $item) {
                $orderItemsToInsert[] = [
                    'order_id' => $order->id,
                    'pizza_id' => $item['pizza_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'customizations' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            OrderItem::insert($orderItemsToInsert);

            // Clear cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido creado exitosamente!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            return back()->withInput()
                ->withError('Error al crear el pedido. Por favor intenta de nuevo.');
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        try {
            $order->load(['customer', 'items.pizza.category']);
            return Inertia::render('Orders/Show', [
                'order' => $order
            ]);
        } catch (Exception $e) {
            Log::error('Error loading order: ' . $e->getMessage());
            return back()->withError('Error loading order. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        try {
            // Only allow editing of pending orders
            if ($order->status !== Order::STATUS_PENDING) {
                return back()->withError('Only pending orders can be edited.');
            }

            $order->load(['customer', 'items.pizza']);
            $customers = Customer::orderBy('name')->get();
            $pizzas = Pizza::with('category')
                ->where('available', true)
                ->orderBy('name')
                ->get();

            return view('orders.edit', compact('order', 'customers', 'pizzas'));
        } catch (Exception $e) {
            Log::error('Error loading order edit form: ' . $e->getMessage());
            return back()->withError('Error loading form. Please try again.');
        }
    }

    /**
     * Update the specified order in storage.
     */
    public function update(OrderRequest $request, Order $order)
    {
        try {
            // Only allow updating of pending orders
            if ($order->status !== Order::STATUS_PENDING) {
                return back()->withError('Only pending orders can be updated.');
            }

            DB::beginTransaction();

            // Update the order
            $order->update([
                'customer_id' => $request->customer_id,
                'total_amount' => $request->total_amount,
                'status' => $request->status ?? $order->status,
                'delivery_method' => $request->delivery_method,
                'payment_method' => $request->payment_method,
                'delivery_address' => $request->delivery_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'delivery_date' => $request->delivery_date,
            ]);

            // Delete existing items and create new ones
            if ($request->has('items')) {
                $order->items()->delete();
                
                foreach ($request->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'pizza_id' => $item['pizza_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'customizations' => $item['customizations'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage());
            return back()->withInput()
                ->withError('Error updating order. Please try again.');
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        try {
            // Only allow deletion of pending or cancelled orders
            if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CANCELLED])) {
                return back()->withError('Only pending or cancelled orders can be deleted.');
            }

            DB::beginTransaction();
            
            // Delete order items first (cascade should handle this, but being explicit)
            $order->items()->delete();
            
            // Delete the order
            $order->delete();
            
            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());
            return back()->withError('Error deleting order. Please try again.');
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status' => 'required|in:' . implode(',', Order::getStatuses()),
            ]);

            $order->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'status' => $order->status,
            ]);
        } catch (Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status.',
            ], 500);
        }
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        try {
            if (!$order->canBeCancelled()) {
                return back()->withError('This order cannot be cancelled.');
            }

            $order->update(['status' => Order::STATUS_CANCELLED]);

            return back()->with('success', 'Order cancelled successfully!');
        } catch (Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            return back()->withError('Error cancelling order. Please try again.');
        }
    }

    /**
     * Get order data for AJAX requests.
     */
    public function getOrderData(Order $order)
    {
        try {
            $order->load(['customer', 'items.pizza']);
            
            return response()->json([
                'success' => true,
                'order' => $order,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching order data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching order data.',
            ], 500);
        }
    }
}
