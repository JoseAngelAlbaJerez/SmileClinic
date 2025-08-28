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
         foreach (range(1, 10) as $i) {

             $branchId = fake()->numberBetween(1, 2);

            $patient = Patient::where('branch_id', $branchId)
                ->inRandomOrder()
                ->first();
             $doctor = User::role('doctor')->where('branch_id', $branchId)
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
