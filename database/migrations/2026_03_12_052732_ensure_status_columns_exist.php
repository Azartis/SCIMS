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
        // add users.status if missing
        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive', 'blocked'])->default('active')->after('role');
            });
        }

        // add pension_distributions.status if missing
        if (Schema::hasTable('pension_distributions') && ! Schema::hasColumn('pension_distributions', 'status')) {
            Schema::table('pension_distributions', function (Blueprint $table) {
                $table->enum('status', ['unclaimed','claimed'])->default('unclaimed')->after('amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasTable('pension_distributions') && Schema::hasColumn('pension_distributions', 'status')) {
            Schema::table('pension_distributions', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
