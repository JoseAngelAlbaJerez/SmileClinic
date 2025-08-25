<?php

namespace Database\Seeders;

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

            DB::table('prescriptions')->insert([
                'doctor_id' => User::role('doctor')->inRandomOrder()->first()->id,
                'patient_id' => fake()->numberBetween(1, 10),
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => fake()->numberBetween(1, 2),
            ]);
        }
    }
}
