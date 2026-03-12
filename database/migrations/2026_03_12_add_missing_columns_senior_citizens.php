<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add missing columns to senior_citizens table
     */
    public function up()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('senior_citizens', 'source_of_income')) {
                $table->string('source_of_income')->nullable()->after('monthly_pension_amount');
            }
            
            if (!Schema::hasColumn('senior_citizens', 'other_income_source_specify')) {
                $table->string('other_income_source_specify')->nullable()->after('other_income_source');
            }
            
            if (!Schema::hasColumn('senior_citizens', 'cause_of_disability')) {
                $table->string('cause_of_disability')->nullable()->after('type_of_disability');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            if (Schema::hasColumn('senior_citizens', 'source_of_income')) {
                $table->dropColumn('source_of_income');
            }
            
            if (Schema::hasColumn('senior_citizens', 'other_income_source_specify')) {
                $table->dropColumn('other_income_source_specify');
            }
            
            if (Schema::hasColumn('senior_citizens', 'cause_of_disability')) {
                $table->dropColumn('cause_of_disability');
            }
        });
    }
};
