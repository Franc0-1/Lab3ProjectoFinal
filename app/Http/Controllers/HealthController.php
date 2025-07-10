<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function check()
    {
        $status = [];
        $overall_status = 'healthy';

        // Verificar base de datos
        try {
            DB::connection()->getPdo();
            $status['database'] = 'connected';
        } catch (\Exception $e) {
            $status['database'] = 'failed: ' . $e->getMessage();
            $overall_status = 'unhealthy';
        }

        // Verificar cache
        try {
            Cache::put('health_check', 'test', 60);
            $cached_value = Cache::get('health_check');
            if ($cached_value === 'test') {
                $status['cache'] = 'working';
            } else {
                $status['cache'] = 'failed: cache not working';
                $overall_status = 'unhealthy';
            }
        } catch (\Exception $e) {
            $status['cache'] = 'failed: ' . $e->getMessage();
            $overall_status = 'unhealthy';
        }

        // Verificar clases de exportación
        try {
            $exports = [];
            $exports['PizzaExport'] = class_exists('App\Exports\PizzaExport');
            $exports['CustomerExport'] = class_exists('App\Exports\CustomerExport');
            $exports['CategoryExport'] = class_exists('App\Exports\CategoryExport');
            $exports['OrderExport'] = class_exists('App\Exports\OrderExport');
            
            $status['exports'] = $exports;
        } catch (\Exception $e) {
            $status['exports'] = 'failed: ' . $e->getMessage();
            $overall_status = 'unhealthy';
        }

        // Verificar Excel facade
        try {
            $status['excel_facade'] = class_exists('Maatwebsite\Excel\Facades\Excel');
        } catch (\Exception $e) {
            $status['excel_facade'] = 'failed: ' . $e->getMessage();
            $overall_status = 'unhealthy';
        }

        return response()->json([
            'status' => $overall_status,
            'timestamp' => now()->toISOString(),
            'checks' => $status,
            'environment' => app()->environment(),
            'version' => config('app.version', '1.0.0')
        ], $overall_status === 'healthy' ? 200 : 500);
    }
}
