<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pizza;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Estadísticas básicas simplificadas
            $stats = [
                'totalPizzas' => Pizza::count(),
                'totalCustomers' => Customer::count(),
                'totalOrders' => Order::count(),
                'totalRevenue' => Order::sum('total') ?? 0,
                'adminUsers' => 1, // Hardcoded temporalmente
                'employeeUsers' => 1, // Hardcoded temporalmente
                'customerUsers' => User::count() - 2, // Aproximación
            ];

            return view('admin.dashboard', compact('stats'));
            
        } catch (\Exception $e) {
            // Si hay error, mostrar dashboard con datos por defecto
            $stats = [
                'totalPizzas' => 0,
                'totalCustomers' => 0,
                'totalOrders' => 0,
                'totalRevenue' => 0,
                'adminUsers' => 1,
                'employeeUsers' => 1,
                'customerUsers' => 1,
            ];

            return view('admin.dashboard', compact('stats'));
        }
    }
}
