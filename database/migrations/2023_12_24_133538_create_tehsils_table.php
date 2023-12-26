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
        Schema::create('tehsils', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('district_id'); // Foreign key to districts table
            $table->foreign('district_id')->references('id')->on('districts');
            $table->timestamps();
        });

        // Insert initial data
        $districtTehsils = [
            'Muzaffarabad' => ['Muzaffarabad', 'Patika (Naseerabad)'],
            'Jhelum Valley (Hattian)' => ['Hattian Bala', 'Chikar', 'Leepa'],
            'Neelum' => ['Athmuqam', 'Sharda'],
            'Bagh' => ['Bagh', 'Harigehl', 'Dhirkot'],
            'Haveli' => ['Haveli', 'Khurshidabad', 'Mumtazabad'],
            'Sudhnuti' => ['Pallandri', 'Mong', 'Trarkhal', 'Baloch'],
            'Poonch' => ['Rawalakot', 'Thorar', 'Hajira', 'Abbaspur'],
            'Mirpur' => ['Mirpur', 'Dadyal'],
            'Bhimber' => ['Bhimber', 'Smahni', 'Barnala'],
            'Kotli' => ['Kotli', 'Sehnsa', 'Fatehpur', 'Charhoi', 'Duliah Jattan', 'Khuiratta'],
        ];

        foreach ($districtTehsils as $districtName => $tehsils) {
            $district_id = DB::table('districts')->where('name', $districtName)->first()->id;

            foreach ($tehsils as $tehsilName) {
                DB::table('tehsils')->insert([
                    'name' => $tehsilName,
                    'district_id' => $district_id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tehsils');
    }
};
