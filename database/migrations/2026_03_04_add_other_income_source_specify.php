<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds other_income_source_specify field to track "Others" income specification
     */
    public function up()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            // Add field after other_income_source
            if (!Schema::hasColumn('senior_citizens', 'other_income_source_specify')) {
                $table->string('other_income_source_specify')->nullable()->after('other_income_source');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            if (Schema::hasColumn('senior_citizens', 'other_income_source_specify')) {
                $table->dropColumn('other_income_source_specify');
            }
        });
    }
};
