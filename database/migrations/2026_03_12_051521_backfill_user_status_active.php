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
        // backfill any existing rows that were created before the status column
        // was added; assume they should be active so login continues to work.
        if (Schema::hasColumn('users', 'status')) {
            \DB::table('users')
                ->whereNull('status')
                ->update(['status' => 'active']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // nothing to rollback; statuses once active should remain
    }
};
