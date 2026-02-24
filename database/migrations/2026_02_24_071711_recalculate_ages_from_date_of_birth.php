<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Recalculate ages from date_of_birth for all records that have a valid date_of_birth
        DB::statement("
            UPDATE senior_citizens 
            SET age = TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())
            WHERE date_of_birth IS NOT NULL AND date_of_birth < CURDATE()
        ");
        
        // Set age to 0 for any records still without valid date_of_birth
        DB::table('senior_citizens')
            ->whereNull('date_of_birth')
            ->orWhere('date_of_birth', '>=', now())
            ->update(['age' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set all ages back to 0 on rollback
        DB::table('senior_citizens')->update(['age' => 0]);
    }
};
