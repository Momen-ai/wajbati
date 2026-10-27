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
        Schema::table('favourites', function (Blueprint $table) {
            $table->foreignId('meal_id')->nullable()->after('chef_id')->constrained('meals')->nullOnDelete();
            $table->unique(['user_id', 'chef_id', 'meal_id'], 'user_chef_meal_fav_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favourites', function (Blueprint $table) {
            $table->dropUnique('user_chef_meal_fav_unique');
            $table->dropForeign(['meal_id']);
            $table->dropColumn('meal_id');
        });
    }
};
