<?php

namespace Database\Seeders;

use App\Models\Analysis;
use Database\Factories\AppointementAnalysisFactory;
use Database\Factories\AppointementMedicamentFactory;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Medicament;
use App\Models\CompteRendu;
use App\Models\Certificate;
use App\Models\CaseDescription;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 50 patients
        $patients = Patient::factory()->count(50)->create();
        $analysis = Analysis::factory()->count(50)->create();
        $appointments = Appointment::factory()->count(50)->create();
        $medicaments = Medicament::factory()->count(50)->create();
        $compteRendus = CompteRendu::factory()->count(50)->create();
        $certificates = Certificate::factory()->count(50)->create();
        $caseDescriptions = CaseDescription::factory()->count(50)->create();


        $appointments->each(function ($appointments) use ($medicaments) {
            $appointments->medicaments()->attach(
                $medicaments->random(rand(1, 3))->pluck('ID_Medicament')->toArray()
            );
        });

        $appointments->each(function ($appointments) use ($analysis) {
            $appointments->analyses()->attach(
                $analysis->random(rand(1, 3))->pluck('ID_Analyse')->toArray()
            );
        });




    }
}
