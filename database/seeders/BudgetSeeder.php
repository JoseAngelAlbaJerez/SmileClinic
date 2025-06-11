<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       foreach (range(1, 10) as $i) {

            DB::table('budgets')->insert([
                'type' => fake()->randomElement(['Credito','Contado']),
                'doctor_id' => User::role('doctor')->inRandomOrder()->first()->id,
                'patient_id' => fake()->numberBetween(1, 10),
               'total'=> fake()->numberBetween(1000, 50000),
                'emission_date' => now(),
                'expiration_date' => now()->addMonth(),
                'active' => true,
                'created_at' => now(),
                'updated_at'=> now(),
            ]);
        }
    }
}
