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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('senior_citizen_id')
                ->constrained('senior_citizens')
                ->cascadeOnDelete();

            // Family Member Information
            $table->string('name');
            $table->string('relationship'); // Child, Grandchild, Spouse, Sibling, etc.
            $table->integer('age')->nullable();
            $table->string('civil_status')->nullable(); // Single, Married, Widowed, etc.
            $table->string('occupation')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable()->default(0);
            $table->text('address')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('family_members');
    }
};
