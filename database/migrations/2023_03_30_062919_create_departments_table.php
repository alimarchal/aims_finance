<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });


        $data = [
            ['id' => 1, 'name' => 'ER', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 2, 'name' => 'Medical', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 3, 'name' => 'Skin', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 4, 'name' => 'Psychiatry', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 5, 'name' => 'Nutrition', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 6, 'name' => 'Nephrology', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 7, 'name' => 'Female', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 8, 'name' => 'Child', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 9, 'name' => 'Dental', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 10, 'name' => 'Gynae', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 11, 'name' => 'Surgical', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 12, 'name' => 'ENT', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 13, 'name' => 'Ortho', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 14, 'name' => 'Urology', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 15, 'name' => 'Eye', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 16, 'name' => 'Cardiac', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 17, 'name' => 'Breast', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 18, 'name' => 'Pulmonology', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
            ['id' => 19, 'name' => 'Oncology', 'created_at' => '2023-06-05 13:31:21', 'updated_at' => '2023-06-05 13:31:21'],
        ];

        DB::table('departments')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
