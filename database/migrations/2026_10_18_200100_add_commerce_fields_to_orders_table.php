<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('phone', 25)->nullable()->after('address');
            $table->decimal('subtotal', 10, 2)->default(0)->after('total');
            $table->decimal('delivery_fee', 10, 2)->default(3)->after('subtotal');
        });

        // Backfill existing orders: subtotal = total - 3, delivery_fee = 3
        DB::table('orders')->where('total', '>', 0)->update([
            'subtotal' => DB::raw('CASE WHEN total > 3 THEN total - 3 ELSE total END'),
            'delivery_fee' => 3.00,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['phone', 'subtotal', 'delivery_fee']);
        });
    }
};
