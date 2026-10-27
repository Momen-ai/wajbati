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
        // Add availability and prep_time_minutes to meals
        Schema::table('meals', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('price');
            $table->integer('prep_time_minutes')->nullable()->after('is_available');
        });

        // Dedupe ratings to keep latest per (user_id, meal_id)
        $duplicates = DB::table('ratings')
            ->select('user_id', 'meal_id', DB::raw('MAX(id) as max_id'))
            ->whereNotNull('meal_id')
            ->groupBy('user_id', 'meal_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            DB::table('ratings')
                ->where('user_id', $dup->user_id)
                ->where('meal_id', $dup->meal_id)
                ->where('id', '<', $dup->max_id)
                ->delete();
        }

        // Add unique constraint on (user_id, meal_id)
        Schema::table('ratings', function (Blueprint $table) {
            $table->unique(['user_id', 'meal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'meal_id']);
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'prep_time_minutes']);
        });
    }
};
