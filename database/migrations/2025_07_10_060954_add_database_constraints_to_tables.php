<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Exception;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Añadir restricciones a la tabla categories
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name', 'categories_name_unique');
            $table->index('active');
            $table->index('sort_order');
        });

        // Añadir restricciones a la tabla customers
        Schema::table('customers', function (Blueprint $table) {
            $table->unique('phone', 'customers_phone_unique');
            $table->unique('email', 'customers_email_unique');
            $table->index('frequent_customer');
            $table->index('neighborhood');
            // Validar que el teléfono tenga un formato válido
            DB::statement('ALTER TABLE customers ADD CONSTRAINT customers_phone_format CHECK (phone REGEXP "^[0-9+\\-\\s()]{7,20}$")');
            // Validar que el email tenga formato válido si no es null
            DB::statement('ALTER TABLE customers ADD CONSTRAINT customers_email_format CHECK (email IS NULL OR email REGEXP "^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$")');
        });

        // Añadir restricciones a la tabla pizzas
        Schema::table('pizzas', function (Blueprint $table) {
            $table->unique(['name', 'category_id'], 'pizzas_name_category_unique');
            $table->index('available');
            $table->index('featured');
            $table->index('price');
            // Validar que el precio sea positivo
            DB::statement('ALTER TABLE pizzas ADD CONSTRAINT pizzas_price_positive CHECK (price > 0)');
            // Validar que el tiempo de preparación sea positivo
            DB::statement('ALTER TABLE pizzas ADD CONSTRAINT pizzas_preparation_time_positive CHECK (preparation_time > 0)');
        });

        // Añadir restricciones a la tabla orders
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('delivery_method');
            $table->index('payment_method');
            $table->index('created_at');
            $table->index('estimated_delivery');
            // Validar que los montos sean positivos
            DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_subtotal_positive CHECK (subtotal >= 0)');
            DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_delivery_fee_positive CHECK (delivery_fee >= 0)');
            DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_total_positive CHECK (total > 0)');
            // Validar que el total sea igual a subtotal + delivery_fee
            DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_total_calculation CHECK (total = subtotal + delivery_fee)');
        });

        // Añadir restricciones a la tabla order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['order_id', 'pizza_id']);
            // Validar que quantity sea positivo
            DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_quantity_positive CHECK (quantity > 0)');
            // Validar que unit_price sea positivo
            DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_unit_price_positive CHECK (unit_price > 0)');
            // Validar que total_price sea positivo
            DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_total_price_positive CHECK (total_price > 0)');
            // Validar que total_price = quantity * unit_price
            DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_total_calculation CHECK (total_price = quantity * unit_price)');
        });

        // Añadir restricciones a la tabla users
        Schema::table('users', function (Blueprint $table) {
            $table->index('email_verified_at');
            $table->index('created_at');
        });

        // Añadir restricciones a la tabla promotions si existe
        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                $table->index('active');
                $table->index('start_date');
                $table->index('end_date');
                // Validar que la fecha de fin sea posterior a la de inicio
                DB::statement('ALTER TABLE promotions ADD CONSTRAINT promotions_dates_valid CHECK (end_date >= start_date)');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar restricciones de customers
        try {
            DB::statement('ALTER TABLE customers DROP CONSTRAINT customers_phone_format');
            DB::statement('ALTER TABLE customers DROP CONSTRAINT customers_email_format');
        } catch (Exception $e) {
            // Ignorar errores si las restricciones no existen
        }

        // Eliminar restricciones de pizzas
        try {
            DB::statement('ALTER TABLE pizzas DROP CONSTRAINT pizzas_price_positive');
            DB::statement('ALTER TABLE pizzas DROP CONSTRAINT pizzas_preparation_time_positive');
        } catch (Exception $e) {
            // Ignorar errores si las restricciones no existen
        }

        // Eliminar restricciones de orders
        try {
            DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_subtotal_positive');
            DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_delivery_fee_positive');
            DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_total_positive');
            DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_total_calculation');
        } catch (Exception $e) {
            // Ignorar errores si las restricciones no existen
        }

        // Eliminar restricciones de order_items
        try {
            DB::statement('ALTER TABLE order_items DROP CONSTRAINT order_items_quantity_positive');
            DB::statement('ALTER TABLE order_items DROP CONSTRAINT order_items_unit_price_positive');
            DB::statement('ALTER TABLE order_items DROP CONSTRAINT order_items_total_price_positive');
            DB::statement('ALTER TABLE order_items DROP CONSTRAINT order_items_total_calculation');
        } catch (Exception $e) {
            // Ignorar errores si las restricciones no existen
        }

        // Eliminar restricciones de promotions
        if (Schema::hasTable('promotions')) {
            try {
                DB::statement('ALTER TABLE promotions DROP CONSTRAINT promotions_dates_valid');
            } catch (Exception $e) {
                // Ignorar errores si las restricciones no existen
            }
        }

        // Eliminar índices únicos y regulares
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_name_unique');
            $table->dropIndex(['active']);
            $table->dropIndex(['sort_order']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_phone_unique');
            $table->dropUnique('customers_email_unique');
            $table->dropIndex(['frequent_customer']);
            $table->dropIndex(['neighborhood']);
        });

        Schema::table('pizzas', function (Blueprint $table) {
            $table->dropUnique('pizzas_name_category_unique');
            $table->dropIndex(['available']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['price']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['delivery_method']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['estimated_delivery']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id', 'pizza_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email_verified_at']);
            $table->dropIndex(['created_at']);
        });

        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                $table->dropIndex(['active']);
                $table->dropIndex(['start_date']);
                $table->dropIndex(['end_date']);
            });
        }
    }
};
