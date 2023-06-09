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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title', 5)->nullable();
            $table->string('first_name', 60)->nullable();
            $table->string('last_name', 60)->nullable();
            $table->string('father_husband_name', 60)->nullable();
            $table->unsignedSmallInteger('age')->nullable();
            $table->string('years_months')->nullable();
            $table->date('dob')->nullable();
            $table->boolean('sex')->nullable();
            $table->string('blood_group')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('mobile', 12)->nullable();
            $table->boolean('email_alert')->default(false);
            $table->boolean('mobile_alert')->default(false);
            $table->string('cnic', 15)->nullable();
            $table->boolean('government_non_gov')->nullable();
            $table->foreignId('government_department_id')->nullable()->constrained();
            $table->string('designation')->nullable();
            $table->string('government_card_no', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
