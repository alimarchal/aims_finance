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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->string('unit_ward')->nullable();
            $table->string('disease')->nullable();
            $table->string('category')->nullable();
            $table->string('nok_name')->nullable();
            $table->string('relation_with_patient')->nullable();
            $table->string('village')->nullable();
            $table->string('tehsil')->nullable();
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->string('cell_no')->nullable();
            $table->string('cnic_no')->nullable();
            $table->enum('status',['Yes','No'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
