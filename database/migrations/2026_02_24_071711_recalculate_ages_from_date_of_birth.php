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
        // Use a driver‑agnostic approach so tests running on sqlite don't blow up.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE senior_citizens \
                SET age = TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())\
                WHERE date_of_birth IS NOT NULL AND date_of_birth < CURDATE()");
        } else {
            // fallback: iterate records in PHP and update individually
            $records = DB::table('senior_citizens')
                ->whereNotNull('date_of_birth')
                ->where('date_of_birth', '<', now())
                ->get(['id', 'date_of_birth']);

            foreach ($records as $rec) {
                $age = \Carbon\Carbon::parse($rec->date_of_birth)->age;
                DB::table('senior_citizens')->where('id', $rec->id)->update(['age' => $age]);
            }
        }
        
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
