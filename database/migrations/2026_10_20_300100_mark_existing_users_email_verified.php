<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Marks all existing users with null email_verified_at as verified
     * so that introducing MustVerifyEmail does not lock out pre-existing accounts.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update([
                'email_verified_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // One-way data migration to prevent user lockout; cannot infer which were previously null safely.
    }
};
