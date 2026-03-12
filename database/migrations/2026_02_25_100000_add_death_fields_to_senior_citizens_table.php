<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            $table->date('date_of_death')->nullable()->after('date_of_birth');
            $table->string('cause_of_death')->nullable()->after('date_of_death');
            $table->string('death_certificate_number')->nullable()->after('cause_of_death');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            $table->dropColumn(['date_of_death', 'cause_of_death', 'death_certificate_number']);
        });
    }
};
