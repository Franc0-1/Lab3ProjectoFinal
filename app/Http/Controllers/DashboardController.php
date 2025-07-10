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
        // Obtener estadísticas básicas con consultas optimizadas
        $stats = $this->getOptimizedStats();

        return view('admin.dashboard', compact('stats'));
    }

    private function getOptimizedStats()
    {
        // Optimización 1: Usar una sola consulta para órdenes con agregaciones
        $orderStats = Order::selectRaw('COUNT(*) as total_orders, SUM(total) as total_revenue')
            ->first();

        // Optimización 2: Contar usuarios por roles en una sola consulta
        $userRoleStats = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name as role', DB::raw('COUNT(*) as count'))
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->groupBy('roles.name')
            ->pluck('count', 'role')
            ->toArray();

        // Optimización 3: Contar customers y total de usuarios en consultas separadas pero eficientes
        $customerCount = Customer::count();
        $totalUsers = User::count();

        return [
            'totalPizzas' => Pizza::count(), // Mejor obtener el count real
            'totalOrders' => $orderStats->total_orders ?? 0,
            'totalCustomers' => $customerCount,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $orderStats->total_revenue ?? 0,
            'adminUsers' => $userRoleStats['admin'] ?? 0,
            'employeeUsers' => $userRoleStats['employee'] ?? 0,
            'customerUsers' => $userRoleStats['customer'] ?? 0,
        ];
    }
}
