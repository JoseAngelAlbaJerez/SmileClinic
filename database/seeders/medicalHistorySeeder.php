<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\MedicalHistory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class medicalHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 3) as $i) {

            $branchId = Branch::inRandomOrder();
            $complications = fake()->boolean();
            $alergies = fake()->boolean();
            $drugs = fake()->boolean();
            $patient = User::role('patient')->inRandomOrder()
                ->first();
            MedicalHistory::create([
                'patient_id' => $patient->id,
                'doctor_id' => User::role('doctor')->inRandomOrder()->first()->id,
                'complications' => $complications,
                'complications_detail' => $complications ? fake()->sentence() : '',
                'alergies' => $alergies,
                'alergies_detail' => $alergies ? fake()->word() : '',
                'drugs' => $drugs,
                'drugs_detail' => $drugs ? fake()->word() : '',
            ]);
        }
    }
}
