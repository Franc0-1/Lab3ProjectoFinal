<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Pizza;

class TestDashboard extends Command
{
    protected $signature = 'test:dashboard';
    protected $description = 'Test dashboard data and identify issues';

    public function handle()
    {
        $this->info('Testing dashboard components...');

        try {
            $this->info('Pizza count: ' . Pizza::count());
            $this->info('Customer count: ' . Customer::count());
            $this->info('Order count: ' . Order::count());
            $this->info('Order total revenue: ' . Order::sum('total'));
        } catch (\Exception $e) {
            $this->error('Error with basic counts: ' . $e->getMessage());
        }

        try {
            $this->info('Testing role-based queries...');
            $adminCount = User::role('admin')->count();
            $this->info('Admin users: ' . $adminCount);
        } catch (\Exception $e) {
            $this->error('Error with role queries: ' . $e->getMessage());
            $this->error('This is likely the cause of the 500 error');
        }

        try {
            $this->info('Testing Order relationships...');
            $orders = Order::with(['customer', 'items.pizza'])->limit(1)->get();
            $this->info('Order relationships test passed');
        } catch (\Exception $e) {
            $this->error('Error with Order relationships: ' . $e->getMessage());
        }

        return 0;
    }
}
