<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request): Response
    {
        $query = Customer::query();

        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('neighborhood', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('frequent') && $request->frequent !== '') {
            $query->where('frequent_customer', (bool) $request->frequent);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['name', 'phone', 'email', 'created_at', 'frequent_customer'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Paginate results
        $customers = $query->withCount('orders')->paginate(15)->withQueryString();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search', 'frequent', 'sort', 'direction']),
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): Response
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'required|string|max:500',
            'neighborhood' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'frequent_customer' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $customer = Customer::create($validatedData);

            DB::commit();

            return redirect()->route('customers.index')
                ->with('success', 'Customer created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating customer: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the customer. Please try again.');
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): Response
    {
        // Load customer with related orders and order items
        $customer->load([
            'orders' => function ($query) {
                $query->with(['orderItems.pizza', 'orderItems.size'])
                      ->orderBy('created_at', 'desc');
            }
        ]);

        // Get customer statistics
        $stats = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->sum('total_amount'),
            'average_order_value' => $customer->orders->count() > 0 
                ? $customer->orders->avg('total_amount') 
                : 0,
            'last_order_date' => $customer->orders->first()?->created_at,
        ];

        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): Response
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'required|string|max:500',
            'neighborhood' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'frequent_customer' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $customer->update($validatedData);

            DB::commit();

            return redirect()->route('customers.index')
                ->with('success', 'Customer updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating customer: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the customer. Please try again.');
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Check if customer has orders
            if ($customer->orders()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete customer with existing orders.');
            }

            $customer->delete();

            DB::commit();

            return redirect()->route('customers.index')
                ->with('success', 'Customer deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting customer: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the customer. Please try again.');
        }
    }

    /**
     * Search customers for AJAX requests (used in order creation).
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email', 'address', 'neighborhood']);

        return response()->json($customers);
    }

    /**
     * Toggle frequent customer status.
     */
    public function toggleFrequent(Customer $customer): JsonResponse
    {
        try {
            $customer->update([
                'frequent_customer' => !$customer->frequent_customer
            ]);

            return response()->json([
                'success' => true,
                'frequent_customer' => $customer->frequent_customer,
                'message' => $customer->frequent_customer 
                    ? 'Customer marked as frequent' 
                    : 'Customer unmarked as frequent'
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling frequent customer status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating customer status.'
            ], 500);
        }
    }

    /**
     * Get frequent customers for quick selection.
     */
    public function frequent(): JsonResponse
    {
        $customers = Customer::frequent()
            ->select('id', 'name', 'phone', 'email', 'address', 'neighborhood')
            ->orderBy('name')
            ->get();

        return response()->json($customers);
    }

    /**
     * Bulk actions for customers.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:delete,mark_frequent,unmark_frequent',
            'customer_ids' => 'required|array|min:1',
            'customer_ids.*' => 'exists:customers,id',
        ]);

        try {
            DB::beginTransaction();

            $customers = Customer::whereIn('id', $request->customer_ids);

            switch ($request->action) {
                case 'delete':
                    // Check if any customers have orders
                    $customersWithOrders = $customers->whereHas('orders')->count();
                    if ($customersWithOrders > 0) {
                        return redirect()->back()
                            ->with('error', 'Cannot delete customers with existing orders.');
                    }
                    
                    $count = $customers->delete();
                    $message = "Deleted {$count} customers successfully.";
                    break;

                case 'mark_frequent':
                    $count = $customers->update(['frequent_customer' => true]);
                    $message = "Marked {$count} customers as frequent.";
                    break;

                case 'unmark_frequent':
                    $count = $customers->update(['frequent_customer' => false]);
                    $message = "Unmarked {$count} customers as frequent.";
                    break;
            }

            DB::commit();

            return redirect()->route('customers.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred while performing the bulk action. Please try again.');
        }
    }
}
