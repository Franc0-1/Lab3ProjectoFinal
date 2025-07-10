<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.pizza']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        $customers = Customer::all();
        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];

        return view('admin.orders.index', compact('orders', 'customers', 'statuses'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $customers = Customer::all();
        $pizzas = Pizza::where('is_available', true)->get();
        return view('admin.orders.create', compact('customers', 'pizzas'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.pizza_id' => 'required|exists:pizzas,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            // Calcular total
            $total = 0;
            foreach ($request->items as $item) {
                $pizza = Pizza::find($item['pizza_id']);
                $total += $pizza->price * $item['quantity'];
            }

            // Crear orden
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'total' => $total,
                'status' => 'pending',
                'delivery_address' => $request->delivery_address,
                'notes' => $request->notes,
            ]);

            // Crear items de la orden
            foreach ($request->items as $item) {
                $pizza = Pizza::find($item['pizza_id']);
                $order->items()->create([
                    'pizza_id' => $item['pizza_id'],
                    'quantity' => $item['quantity'],
                    'price' => $pizza->price,
                ]);
            }
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Orden creada exitosamente.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.pizza']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $customers = Customer::all();
        $pizzas = Pizza::where('is_available', true)->get();
        $order->load(['customer', 'items.pizza']);
        return view('admin.orders.edit', compact('order', 'customers', 'pizzas'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
            'delivery_address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'customer_id' => $request->customer_id,
            'status' => $request->status,
            'delivery_address' => $request->delivery_address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Orden actualizada exitosamente.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Orden eliminada exitosamente.');
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Estado de la orden actualizado exitosamente.');
    }

    /**
     * Get orders statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'preparing_orders' => Order::where('status', 'preparing')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->sum('total'),
        ];

        return view('admin.orders.statistics', compact('stats'));
    }
}
