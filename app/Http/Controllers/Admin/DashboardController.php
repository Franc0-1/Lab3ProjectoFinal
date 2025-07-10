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
        // Estadísticas básicas
        $stats = [
            'totalPizzas' => Pizza::count(),
            'totalCustomers' => Customer::count(),
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::sum('total'),
            'adminUsers' => User::role('admin')->count(),
            'employeeUsers' => User::role('employee')->count(),
            'customerUsers' => User::role('customer')->count(),
        ];

        // Órdenes recientes
        $recentOrders = Order::with(['customer', 'items.pizza'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Pizzas más vendidas
        $topPizzas = DB::table('order_items')
            ->join('pizzas', 'order_items.pizza_id', '=', 'pizzas.id')
            ->select('pizzas.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('pizzas.id', 'pizzas.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Clientes frecuentes
        $topCustomers = Customer::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        // Ventas por mes (últimos 6 meses)
        $monthlySales = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total_sales'),
            DB::raw('COUNT(*) as order_count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recentOrders', 
            'topPizzas', 
            'topCustomers', 
            'monthlySales'
        ));
    }
}
