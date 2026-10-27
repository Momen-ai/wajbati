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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('meal_name')->nullable()->after('meal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('meal_name');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
