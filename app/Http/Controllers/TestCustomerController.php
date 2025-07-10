<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestCustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $customers = Customer::withCount('orders')->paginate(15);
            
            // Test with regular blade view first
            return response()->json([
                'success' => true,
                'customers_count' => $customers->total(),
                'customers' => $customers->items(),
                'filters' => $request->only(['search', 'frequent', 'sort', 'direction']),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    public function inertiaTest(Request $request)
    {
        try {
            $customers = Customer::withCount('orders')->paginate(15);
            
            return Inertia::render('Customers/Index', [
                'customers' => $customers,
                'filters' => $request->only(['search', 'frequent', 'sort', 'direction']),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
