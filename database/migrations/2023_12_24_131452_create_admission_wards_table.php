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
        Schema::create('admission_wards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert initial data
        $wards = [
            'Medical Ward I',
            'Medical Ward II',
            'Pediatrics Ward',
            'Gynecology Ward',
            'Urology Ward',
            'Surgical Ward I',
            'Surgical Ward II',
            'Labor Room',
            'Orthopaedic Ward',
            'CCU Ward',
            'ICU Ward',
            'Nephrology Ward',
            'Pulmonology Ward',
        ];

        foreach ($wards as $ward) {
            DB::table('admission_wards')->insert([
                'name' => $ward,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_wards');
    }
};
