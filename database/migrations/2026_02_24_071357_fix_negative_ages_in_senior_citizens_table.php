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
        // Update all negative ages to 0
        DB::table('senior_citizens')
            ->where('age', '<', 0)
            ->update(['age' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed - this is a data fix
    }
};
