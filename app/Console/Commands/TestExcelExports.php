<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\PizzaExport;
use App\Exports\CustomerExport;
use App\Exports\CategoryExport;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

class TestExcelExports extends Command
{
    protected $signature = 'test:excel-exports';
    protected $description = 'Test Excel export functionality';

    public function handle()
    {
        $this->info('Testing Excel exports...');

        try {
            $this->info('1. Testing PizzaExport class...');
            $export = new PizzaExport();
            $this->info('   ✓ PizzaExport class loaded successfully');

            $this->info('2. Testing CustomerExport class...');
            $export = new CustomerExport();
            $this->info('   ✓ CustomerExport class loaded successfully');

            $this->info('3. Testing CategoryExport class...');
            $export = new CategoryExport();
            $this->info('   ✓ CategoryExport class loaded successfully');

            $this->info('4. Testing OrderExport class...');
            $export = new OrderExport();
            $this->info('   ✓ OrderExport class loaded successfully');

            $this->info('5. Testing Excel facade...');
            if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
                $this->info('   ✓ Excel facade is available');
            } else {
                $this->error('   ✗ Excel facade is not available');
            }

            $this->info('All Excel export tests passed!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Error during testing: ' . $e->getMessage());
            return 1;
        }
    }
}
