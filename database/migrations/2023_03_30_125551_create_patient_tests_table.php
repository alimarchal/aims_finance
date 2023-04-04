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
        Schema::create('patient_tests', function (Blueprint $table) {
            $table->id();
            $table->string('patient_test_id');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('lab_test_id')->constrained();
            $table->decimal('hif_amount',15,2);
            $table->decimal('government_amount',15,2);
            $table->decimal('total_amount',15,2);
            $table->boolean('government_non_gov')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_tests');
    }
};
