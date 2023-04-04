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
            $table->string('name',60)->nullable();
            $table->string('father_son_do',60)->nullable();
            $table->boolean('sex')->nullable();
            $table->string('cnic',15)->nullable();
            $table->string('mobile_no',12)->nullable();
            $table->boolean('government_non_gov')->nullable();
            $table->string('department_name')->nullable();
            $table->string('government_card_no',20)->nullable();
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
