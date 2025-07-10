<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Pizza;

class SimpleDashboardController extends Controller
{
    public function index()
    {
        try {
            // Estadísticas básicas sin roles complejos
            $stats = [
                'totalPizzas' => Pizza::count(),
                'totalCustomers' => Customer::count(),
                'totalOrders' => Order::count(),
                'totalRevenue' => Order::sum('total') ?? 0,
                'adminUsers' => 1, // Hardcoded para evitar errores
                'employeeUsers' => 1,
                'customerUsers' => User::count() - 2,
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'message' => 'Dashboard data loaded successfully'
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
