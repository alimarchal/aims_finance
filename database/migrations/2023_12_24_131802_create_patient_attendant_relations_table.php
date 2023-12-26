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
        Schema::create('patient_attendant_relations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert initial data
        $relations = [
            'Father',
            'Mother',
            'Grandfather',
            'Grandmother',
            'Father-in-Law',
            'Mother-in-Law',
            'Son',
            'Daughter',
            'Brother',
            'Sister',
            'Grandson',
            'Granddaughter',
            'Son-in-Law',
            'Daughter-in-Law',
            'Uncle',
            'Aunt',
            'Cousin',
            'Nephew',
            'Niece',
        ];

        foreach ($relations as $relation) {
            DB::table('patient_attendant_relations')->insert([
                'name' => $relation,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_attendant_relations');
    }
};
