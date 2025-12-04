<?php

namespace Database\Seeders;

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


            $patient = User::role('patient')->inRandomOrder()
                ->first();
            $doctor = User::role('doctor')
                ->inRandomOrder()
                ->first();

            DB::table('prescriptions')->insert([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => fake()->numberBetween(1, 2),
            ]);
        }
    }
}
