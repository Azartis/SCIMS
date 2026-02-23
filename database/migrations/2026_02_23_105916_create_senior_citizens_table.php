<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('senior_citizens', function (Blueprint $table) {
        $table->id();

        // Basic Information
        $table->string('fullname');
        $table->date('date_of_birth');
        $table->integer('age');
        $table->string('gender');
        $table->text('address');
        $table->string('contact_number')->nullable();
        $table->string('osca_id')->unique();

        // Pension / Membership Type
        $table->boolean('sss')->default(false);
        $table->boolean('gsis')->default(false);
        $table->boolean('pvao')->default(false);
        $table->boolean('family_pension')->default(false);
        $table->boolean('brgy_official')->default(false);

        // Status Fields
        $table->boolean('waitlist')->default(false);
        $table->boolean('social_pension')->default(false);

        // Remarks (temporary flexible field)
        $table->text('remarks')->nullable();

        $table->timestamps();
        $table->softDeletes(); // For LGU record safety
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senior_citizens');
    }
};
