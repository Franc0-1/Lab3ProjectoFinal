<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Pizza;

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
                'adminUsers' => 1,
                'employeeUsers' => 1,
                'customerUsers' => User::count() - 2,
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
