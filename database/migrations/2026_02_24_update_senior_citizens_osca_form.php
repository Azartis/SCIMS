<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Extends senior_citizens table with complete OSCA Intake Form fields
     */
    public function up()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            // Personal / Basic Information - Complete
            $table->string('extension_name')->nullable()->after('lastname');
            $table->string('place_of_birth')->nullable()->after('date_of_birth');
            $table->string('civil_status')->nullable()->after('sex');
            $table->string('citizenship')->default('Filipino')->after('civil_status');
            $table->string('religion')->nullable()->after('citizenship');
            $table->string('educational_attainment')->nullable()->after('religion');

            // Health Condition Section
            $table->boolean('with_disability')->default(false)->after('educational_attainment');
            $table->string('type_of_disability')->nullable()->after('with_disability');
            $table->boolean('bedridden')->default(false)->after('type_of_disability');
            $table->boolean('with_assistive_device')->default(false)->after('bedridden');
            $table->string('type_of_assistive_device')->nullable()->after('with_assistive_device');
            $table->boolean('with_critical_illness')->default(false)->after('type_of_assistive_device');
            $table->text('specify_illness')->nullable()->after('with_critical_illness');
            $table->boolean('philhealth_member')->default(false)->after('specify_illness');
            $table->string('philhealth_id')->nullable()->after('philhealth_member');

            // Source of Income Section
            $table->boolean('is_pensioner')->default(false)->after('philhealth_id');
            $table->string('pension_type')->nullable()->after('is_pensioner'); // SSS, GSIS, PVAO, Private, Others
            $table->decimal('monthly_pension_amount', 10, 2)->nullable()->default(0)->after('pension_type');
            $table->text('other_income_source')->nullable()->after('monthly_pension_amount');
            $table->decimal('total_monthly_income', 10, 2)->nullable()->default(0)->after('other_income_source');

            // Classification / Categorization Helpers
            $table->boolean('is_indigent')->default(false)->after('total_monthly_income');
            $table->string('age_range')->nullable()->after('is_indigent'); // 60-69, 70-79, 80+
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            $table->dropColumn([
                'extension_name',
                'place_of_birth',
                'civil_status',
                'citizenship',
                'religion',
                'educational_attainment',
                'with_disability',
                'type_of_disability',
                'bedridden',
                'with_assistive_device',
                'type_of_assistive_device',
                'with_critical_illness',
                'specify_illness',
                'philhealth_member',
                'philhealth_id',
                'is_pensioner',
                'pension_type',
                'monthly_pension_amount',
                'other_income_source',
                'total_monthly_income',
                'is_indigent',
                'age_range',
            ]);
        });
    }
};
