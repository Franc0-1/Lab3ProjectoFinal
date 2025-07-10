<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExcelExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pizza;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Order;
use Inertia\Inertia;

class ReportController extends Controller
{
    // Reportes de Pizzas
    public function exportPizzasExcel(ExcelExportService $exportService)
    {
        try {
            $pizzas = Pizza::with('category')->get();
            $formattedData = $exportService->formatPizzasData($pizzas);
            
            return $exportService->exportToCsv(
                collect($formattedData['data']),
                $formattedData['headers'],
                'pizzas_' . date('Y-m-d') . '.csv'
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar archivo Excel: ' . $e->getMessage()], 500);
        }
    }

    public function exportPizzasPdf()
    {
        $pizzas = Pizza::with('category')->get();
        $pdf = Pdf::loadView('reports.pizzas', compact('pizzas'));
        return $pdf->download('pizzas_' . date('Y-m-d') . '.pdf');
    }

    // Reportes de Clientes
    public function exportCustomersExcel(ExcelExportService $exportService)
    {
        try {
            $customers = Customer::with('orders')->get();
            $formattedData = $exportService->formatCustomersData($customers);
            
            return $exportService->exportToCsv(
                collect($formattedData['data']),
                $formattedData['headers'],
                'clientes_' . date('Y-m-d') . '.csv'
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar archivo Excel: ' . $e->getMessage()], 500);
        }
    }

    public function exportCustomersPdf()
    {
        $customers = Customer::with('orders')->get();
        $pdf = Pdf::loadView('reports.customers', compact('customers'));
        return $pdf->download('clientes_' . date('Y-m-d') . '.pdf');
    }

    // Reportes de Categorías
    public function exportCategoriesExcel(ExcelExportService $exportService)
    {
        try {
            $categories = Category::with('pizzas')->get();
            $formattedData = $exportService->formatCategoriesData($categories);
            
            return $exportService->exportToCsv(
                collect($formattedData['data']),
                $formattedData['headers'],
                'categorias_' . date('Y-m-d') . '.csv'
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar archivo Excel: ' . $e->getMessage()], 500);
        }
    }

    public function exportCategoriesPdf()
    {
        $categories = Category::with('pizzas')->get();
        $pdf = Pdf::loadView('reports.categories', compact('categories'));
        return $pdf->download('categorias_' . date('Y-m-d') . '.pdf');
    }

    // Reportes de Órdenes
    public function exportOrdersExcel(ExcelExportService $exportService)
    {
        try {
            $orders = Order::with('customer', 'items.pizza')->get();
            $formattedData = $exportService->formatOrdersData($orders);
            
            return $exportService->exportToCsv(
                collect($formattedData['data']),
                $formattedData['headers'],
                'ordenes_' . date('Y-m-d') . '.csv'
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar archivo Excel: ' . $e->getMessage()], 500);
        }
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
    public function exportExcel(ExcelExportService $exportService)
    {
        return $this->exportPizzasExcel($exportService);
    }

    public function exportPdf()
    {
        return $this->exportPizzasPdf();
    }
}
