<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'orders_status_idx');
            $table->index(['chef_id', 'status'], 'orders_chef_status_idx');
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_idx');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->index('meal_id', 'ratings_meal_id_idx');
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->index(['chef_id', 'is_available'], 'meals_chef_available_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropIndex('meals_chef_available_idx');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex('ratings_meal_id_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_idx');
            $table->dropIndex('orders_chef_status_idx');
            $table->dropIndex('orders_payment_created_idx');
        });
    }
};
