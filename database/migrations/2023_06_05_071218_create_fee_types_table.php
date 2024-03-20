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
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_category_id')->constrained();
            $table->string('type')->nullable();
            $table->decimal('amount',14,2);
            $table->decimal('hif',14,2);
            $table->enum('status',['Normal','Return Fee'])->default('Normal');
            $table->timestamps();
        });


        DB::statement("
            INSERT INTO `fee_types` (`id`, `fee_category_id`, `type`, `amount`, `created_at`, `updated_at`) VALUES
            (1, 1, 'Chit Fee', 10.00, '2023-06-05 09:29:43', '2023-06-05 11:51:47'),
            (2, 1, 'Admission Fee', 150.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (3, 1, 'ECG Fee', 40.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (4, 1, 'Driving Lisence', 100.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (5, 1, 'Birth Certificate', 50.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (6, 2, 'X- Ray Fee', 110.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (7, 2, 'Ultrosound Fee', 100.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (8, 2, 'Special Test', 300.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (9, 3, 'Major Fee', 500.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (10, 3, 'Minor Fee', 300.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (11, 4, 'CTG FEE', 50.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (12, 4, 'Delivery Fee', 200.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (13, 4, 'Ultrasound  Fee', 150.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (14, 4, 'Delivery Fee', 200.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (15, 4, 'Room Charges', 500.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (16, 5, 'Endoscopy', 500.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (17, 5, 'Clonocopy', 1000.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (18, 5, 'ERCP', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (19, 6, 'OPD Fee', 30.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (20, 6, 'ECG Fee', 40.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (21, 6, 'ETT Fee', 350.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (22, 6, 'ECHO Fee', 350.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (23, 7, 'Amalgam Filling', 40.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (24, 7, 'Glass Lonomer Filling', 190.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (25, 7, 'Composite Filling', 80.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (26, 7, 'Temporary Filling', 10.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (27, 7, 'Extractions', 40.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (28, 7, 'Root Cannal Anterior', 400.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (29, 7, 'Root Cannal Posterior', 500.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (30, 7, 'Minor Surgical Procedure', 300.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (31, 7, 'Impactions', 400.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (32, 7, 'Major Operation', 1000.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (33, 7, 'Scalling', 300.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (34, 7, 'Partial Denture', 200.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (35, 7, 'Full Denture', 3000.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (36, 7, 'Orthodontic Removable', 2000.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (37, 7, 'Orthodentic Fixed', 18500.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (38, 7, 'Crown Bridge Work', 1050.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (39, 7, 'Partial Denture Repair', 100.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (40, 7, 'Full Denture Repair', 300.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (41, 7, 'Dental x-Ray', 80.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (42, 7, 'OPG X-Ray', 350.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (43, 8, 'Blood CP', 60.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (44, 8, 'PT', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (45, 8, 'APTT', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (46, 8, 'Peripherial Film', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (47, 8, 'Bone Marrow', 360.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (48, 8, 'BT-CT', 220.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (49, 8, 'Malaria Parasite', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (50, 8, 'Semen Analysis', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (51, 8, 'Fluid/CSF', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (52, 8, 'ESR', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (53, 8, 'LT Bodies', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (54, 8, 'ABO Grouping', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (55, 9, 'S.Billirubin', 320.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (56, 9, 'ALP', 320.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (57, 9, 'ALT(GPT)', 320.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (58, 9, 'LDH', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (59, 9, 'CPK (NAC)', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (60, 9, 'CPK (MB)', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (61, 9, 'AST (GOT)', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (62, 9, 'Cholesterol', 220.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (63, 9, 'Triglyceriders', 220.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (64, 9, 'LDL', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (65, 9, 'Blood Sugar  F+R', 220.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (66, 9, 'S.Urea', 220.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (67, 9, 'S.Creatinin', 220.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (68, 9, 'Uric Acid', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (69, 9, 'Serum Amylase', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (70, 9, 'Serum Protein', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (71, 9, 'Serum Albumin', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (72, 9, 'Serum Electrolytes', 290.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (73, 9, 'Serum Calcium', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (74, 9, 'ABGS', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (75, 9, 'CSF Protein', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (76, 10, 'Routine C/S ', 152.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (77, 10, 'Fungal C/S', 152.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (78, 10, 'Blood Culture', 152.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (79, 10, 'Sputum RE', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (80, 11, 'widal Test', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (81, 11, 'Typhidot', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (82, 11, 'ASO Titre', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (83, 11, 'RA Factor', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (84, 11, 'Montoux Test', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (85, 11, 'CRP', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (86, 11, 'H.Polox / Stool', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (87, 11, 'Pylori Blood.', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (88, 11, 'Brucella', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (89, 11, 'Total Screening Hepatites B', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (90, 11, 'Positive Case Hepatites B', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (91, 11, 'Total Screening Hepatites C', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (92, 11, 'Positive Case Hepatitis C', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (93, 11, 'Total Screening HIV/AIDS', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (94, 11, 'Positive Case HIV/AIDS', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (95, 11, 'ANF/VDRL', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (96, 11, 'Sputum AFB Negative', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (97, 11, 'Dangue ANS/Serology', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (98, 11, 'HBAIC', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (99, 11, 'ANA', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (100, 11, 'Covid PCR', 0.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (101, 12, 'Urine R/E', 30.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (102, 12, 'Stool R/E', 30.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (103, 12, 'Pregnancy Test', 30.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (104, 12, 'Stool Occult Blood', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (105, 12, 'Mgt Serum Mgt', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (106, 12, 'PO4', 120.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43'),
            (107, 13, 'Chit Fee', 30.00, '2023-06-05 09:29:43', '2023-06-05 09:29:43');
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_types');
    }
};
