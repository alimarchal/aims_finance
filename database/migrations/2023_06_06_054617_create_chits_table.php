<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('fee_type_id')->nullable()->constrained();
            $table->foreignId('government_department_id')->nullable()->constrained();
            $table->timestamp('issued_date', 0)->useCurrent();
            $table->decimal('amount', 15, 2);
            $table->boolean('ipd_opd', 1);
            $table->boolean('payment_status')->default(1);
            $table->boolean('government_non_gov')->nullable();
            $table->string('government_card_no',15)->nullable();
            $table->string('designation',30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chits');
    }
};
