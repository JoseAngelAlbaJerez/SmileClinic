<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 100) as $i) {

            $patient = User::role('patient')->inRandomOrder()->first();
            $doctor = User::role('doctor')->inRandomOrder()->first();
            $branch = Branch::inRandomOrder()->first();
            $prescription = \App\Models\Prescription::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'active' => $branch->id,
                'branch_id' => fake()->numberBetween(1, 2),
            ]);

            $prescription->prescriptionsDetails()->create([
                'description'   => fake()->sentence(6),
                'drug_id'       => fake()->numberBetween(1, 50),
                'branch_id'     => $prescription->branch_id,
                'active'        => true,
            ]);
        }
    }
}
