<?php

namespace Database\Seeders;

use App\Models\Patient;
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

            $branchId = fake()->numberBetween(1, 2);

            $patient = Patient::where('branch_id', $branchId)
                ->inRandomOrder()
                ->first();

            DB::table('budgets')->insert([
                'type' => fake()->randomElement(['Credito', 'Contado']),
                'doctor_id' => User::role('doctor')->where('branch_id', $branchId)->inRandomOrder()->first()->id,
                'patient_id' => $patient?->id,
                'total' => fake()->numberBetween(1000, 50000),
                'emission_date' => now(),
                'currency' => fake()->randomElement(['DOP', 'US']),
                'expiration_date' => now()->addMonth(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => $branchId,
            ]);
        }
    }
}
