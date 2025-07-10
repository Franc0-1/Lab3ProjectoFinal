<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Temporalmente comentado debido a problemas de compatibilidad
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\PizzaExport;
// use App\Exports\CustomerExport;
// use App\Exports\CategoryExport;
// use App\Exports\OrderExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pizza;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Order;
use Inertia\Inertia;

class ReportController extends Controller
{
    // Reportes de Pizzas
    public function exportPizzasExcel()
    {
        // Temporalmente deshabilitado debido a problemas de compatibilidad
        return response()->json(['error' => 'Exportación de Excel temporalmente deshabilitada'], 503);
        // return Excel::download(new PizzaExport, 'pizzas_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPizzasPdf()
    {
        $pizzas = Pizza::with('category')->get();
        $pdf = Pdf::loadView('reports.pizzas', compact('pizzas'));
        return $pdf->download('pizzas_' . date('Y-m-d') . '.pdf');
    }

    // Reportes de Clientes
    public function exportCustomersExcel()
    {
        // Temporalmente deshabilitado debido a problemas de compatibilidad
        return response()->json(['error' => 'Exportación de Excel temporalmente deshabilitada'], 503);
        // return Excel::download(new CustomerExport, 'clientes_' . date('Y-m-d') . '.xlsx');
    }

    public function exportCustomersPdf()
    {
        $customers = Customer::with('orders')->get();
        $pdf = Pdf::loadView('reports.customers', compact('customers'));
        return $pdf->download('clientes_' . date('Y-m-d') . '.pdf');
    }

    // Reportes de Categorías
    public function exportCategoriesExcel()
    {
        // Temporalmente deshabilitado debido a problemas de compatibilidad
        return response()->json(['error' => 'Exportación de Excel temporalmente deshabilitada'], 503);
        // return Excel::download(new CategoryExport, 'categorias_' . date('Y-m-d') . '.xlsx');
    }

    public function exportCategoriesPdf()
    {
        $categories = Category::with('pizzas')->get();
        $pdf = Pdf::loadView('reports.categories', compact('categories'));
        return $pdf->download('categorias_' . date('Y-m-d') . '.pdf');
    }

    // Reportes de Órdenes
    public function exportOrdersExcel()
    {
        // Temporalmente deshabilitado debido a problemas de compatibilidad
        return response()->json(['error' => 'Exportación de Excel temporalmente deshabilitada'], 503);
        // return Excel::download(new OrderExport, 'ordenes_' . date('Y-m-d') . '.xlsx');
    }

    public function exportOrdersPdf()
    {
        $orders = Order::with('customer', 'items.pizza')->get();
        $pdf = Pdf::loadView('reports.orders', compact('orders'));
        return $pdf->download('ordenes_' . date('Y-m-d') . '.pdf');
    }

    // Reporte general
    public function exportGeneralPdf()
    {
        $pizzas = Pizza::with('category')->get();
        $customers = Customer::with('orders')->get();
        $categories = Category::with('pizzas')->get();
        $orders = Order::with('customer', 'items.pizza')->get();
        
        // Estadísticas adicionales
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('total'),
            'average_order' => Order::avg('total'),
            'orders_this_month' => Order::whereMonth('created_at', now()->month)->count(),
            'revenue_this_month' => Order::whereMonth('created_at', now()->month)->sum('total'),
            'top_customer' => Customer::withCount('orders')->orderBy('orders_count', 'desc')->first(),
            'orders_by_status' => Order::groupBy('status')->selectRaw('status, count(*) as count')->get()->pluck('count', 'status')
        ];
        
        $pdf = Pdf::loadView('reports.general', compact('pizzas', 'customers', 'categories', 'orders', 'stats'));
        return $pdf->download('reporte_general_' . date('Y-m-d') . '.pdf');
    }
    
    // Reporte de ventas por período
    public function exportSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        
        $orders = Order::with(['customer', 'items.pizza'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        $salesData = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'average_order' => $orders->avg('total'),
            'orders_by_day' => $orders->groupBy(function($order) {
                return $order->created_at->format('Y-m-d');
            })->map->count(),
            'revenue_by_day' => $orders->groupBy(function($order) {
                return $order->created_at->format('Y-m-d');
            })->map->sum('total')
        ];
        
        $pdf = Pdf::loadView('reports.sales', compact('orders', 'salesData', 'startDate', 'endDate'));
        return $pdf->download('reporte_ventas_' . date('Y-m-d') . '.pdf');
    }
    
    // Reporte de clientes frecuentes
    public function exportFrequentCustomers()
    {
        $customers = Customer::withCount('orders')
            ->with(['orders' => function($query) {
                $query->selectRaw('customer_id, SUM(total) as total_spent')
                    ->groupBy('customer_id');
            }])
            ->having('orders_count', '>=', 3)
            ->orderBy('orders_count', 'desc')
            ->get();
            
        $pdf = Pdf::loadView('reports.frequent-customers', compact('customers'));
        return $pdf->download('clientes_frecuentes_' . date('Y-m-d') . '.pdf');
    }

    // Vista principal de reportes
    public function index()
    {
        $stats = [
            'total_pizzas' => 11, // Pizzas estáticas del menú
            'total_customers' => Customer::count(),
            'total_categories' => 5, // Categorías estimadas
            'total_orders' => Order::count(),
            'frequent_customers' => Customer::where('frequent_customer', true)->count(),
            'active_pizzas' => 11,
            'active_categories' => 5,
        ];

        return view('admin.reports.index', compact('stats'));
    }

    // Métodos legacy para compatibilidad
    public function exportExcel()
    {
        // Temporalmente deshabilitado debido a problemas de compatibilidad
        return response()->json(['error' => 'Exportación de Excel temporalmente deshabilitada'], 503);
        // return $this->exportPizzasExcel();
    }

    public function exportPdf()
    {
        return $this->exportPizzasPdf();
    }
}
