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
        Schema::create('pension_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('senior_citizen_id')->constrained('senior_citizens')->onDelete('cascade');
            $table->date('disbursement_date');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status')->default('unclaimed'); // unclaimed, claimed

            // Authorized representative info when unclaimed
            $table->string('authorized_rep_name')->nullable();
            $table->string('authorized_rep_relationship')->nullable();
            $table->string('authorized_rep_contact')->nullable();

            $table->timestamp('claimed_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pension_distributions');
    }
};
