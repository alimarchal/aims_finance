<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });

        // Create multiple records in the fee_categories table
        $data = [
            ['id' => 1, 'name' => 'Emergency', 'type' => 'General', 'created_at' => '2023-06-05 04:29:25', 'updated_at' => '2023-06-05 04:29:25'],
            ['id' => 2, 'name' => 'X-Ray', 'type' => 'General', 'created_at' => '2023-06-05 04:29:25', 'updated_at' => '2023-06-05 04:29:25'],
            ['id' => 3, 'name' => 'Operation Theater', 'type' => 'General', 'created_at' => '2023-06-05 04:29:25', 'updated_at' => '2023-06-05 04:29:25'],
            ['id' => 4, 'name' => 'Gynae & Obs', 'type' => 'General', 'created_at' => '2023-06-05 04:29:25', 'updated_at' => '2023-06-05 04:29:25'],
            ['id' => 5, 'name' => 'Medical', 'type' => 'General', 'created_at' => '2023-06-05 04:29:25', 'updated_at' => '2023-06-05 04:29:25'],
            ['id' => 6, 'name' => 'Cardiology', 'type' => 'General', 'created_at' => '2023-06-05 04:29:25', 'updated_at' => '2023-06-05 04:29:25'],
            ['id' => 7, 'name' => 'Dental', 'type' => 'General', 'created_at' => '2023-06-05 04:29:25', 'updated_at' => '2023-06-05 04:29:25'],
            ['id' => 8, 'name' => 'Haematology', 'type' => 'Test', 'created_at' => '2023-06-05 09:58:54', 'updated_at' => '2023-06-05 09:58:54'],
            ['id' => 9, 'name' => 'BIO Chemistry', 'type' => 'Test', 'created_at' => '2023-06-05 09:58:54', 'updated_at' => '2023-06-05 09:58:54'],
            ['id' => 10, 'name' => 'Microbiiology', 'type' => 'Test', 'created_at' => '2023-06-05 09:58:54', 'updated_at' => '2023-06-05 09:58:54'],
            ['id' => 11, 'name' => 'Serology', 'type' => 'Test', 'created_at' => '2023-06-05 09:58:54', 'updated_at' => '2023-06-05 09:58:54'],
            ['id' => 12, 'name' => 'Clinical Pathology', 'type' => 'Test', 'created_at' => '2023-06-05 09:58:54', 'updated_at' => '2023-06-05 09:58:54'],
            ['id' => 13, 'name' => 'OPD (Out Door Patient)', 'type' => 'OPD', 'created_at' => '2023-06-05 12:21:58', 'updated_at' => '2023-06-05 12:21:58'],
        ];

        DB::table('fee_categories')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_categories');
    }
};
