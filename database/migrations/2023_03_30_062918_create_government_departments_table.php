<?php

use App\Models\GovernmentDepartment;
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
        Schema::create('government_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        // Department names
        $departmentNames = [
            'IT BOARD',
            'CHIEF SECRETARY OFFICE',
            'LOCAL GOVT',
            'ELEMENTARY & SECONDARY EDUCATION',
            'AJK POLICE',
            'CIVIL DEFENCE',
            'HEALTH',
            'MOHTASIB (OMBUDSMAN)',
            'TOURISM',
            'FINANCE DEPARTMENT AJK',
            'INFORMATION DEPARTMENT',
            'ZAKAT & USHR DEPARTMENT',
            'AJ&K TOURISM & ARCHAEOLOGY DEPARTMENT',
            'TRADE TESTING BOARD',
            'SUPREME COURT OF AJ&K',
            'PUBLIC PROCUREMENT REGULATORY AUTHORITY AJK',
            'ENVIRONMENT PROTECTION AGENCY',
            'PLANNING & DEVELOPMENT DEPARTMENT AJ&K',
            'PHYSICAL PLANNING & HOUSING DEPARTMENT',
            'MINERALS DEPARTMENT AJ&K',
            'LIVESTOCK & DAIRY DEVELOPMENT DEPARTMENT',
            'LAW DEPARTMENT AJ&K',
            'KASHMIR LIBERATION CELL',
            'HOME DEPARTMENT AJ&K',
            'HIGHER EDUCATION OF AJ&K',
            'HIGH COURT OF AJ&K',
            'FORESTRY, WILDLIFE & FISHERIES DEPARTMENT',
            'ELECTION COMMISSION OF AJ&K',
            'EHTESAB BUREAU',
            'DEPARTMENT OF LABOUR WELFARE AND WEIGHTS & MEASURES',
            'DEPARTMENT OF INLAND REVENUE',
            'DEPARTMENT OF INDUSTRIES AND COMMERCE',
            'COMPLAINTS MANAGEMENT SYSTEM',
            'COMMUNICATION & WORKS DEPARTMENT',
            'BOARD OF INVESTMENT (BOI)',
            'BANK OF AJK',
            'AZAD JAMMU KASHMIR MEDICAL COLLEGE',
            'AUQAF & AMOOR-E-DINIA DEPARTMENT',
            'ANTI CORRUPTION ESTABLISHMENT AJ&K',
            'ELECTRICITY',
            'ABBAS INSTITUTE OF MEDICAL SCIENCES',
        ];

        // Insert data into the table
        foreach ($departmentNames as $name) {
            GovernmentDepartment::create(['name' => $name]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_departments');
    }
};
