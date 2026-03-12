<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds National ID, Cause of Disability, and Source of Income fields
     */
    public function up()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            // Add National ID after osca_id
            $table->string('national_id')->nullable()->after('osca_id');
            
            // Add Cause of Disability after type_of_disability
            $table->string('cause_of_disability')->nullable()->after('type_of_disability');
            
            // Add Source of Income after other_income_source
            $table->string('source_of_income')->nullable()->after('other_income_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            $table->dropColumn([
                'national_id',
                'cause_of_disability',
                'source_of_income',
            ]);
        });
    }
};
