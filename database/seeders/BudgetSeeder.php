<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Branch;
use App\Models\Procedure;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach (range(1, 100) as $i) {

            $branch = Branch::inRandomOrder()->first();
            $patient = User::role('patient')->inRandomOrder()
                ->first();
            $procedure = Procedure::inRandomOrder()->first()->id;
            $doctor = User::role('doctor')->inRandomOrder()->first()->id;

            $budget = \App\Models\Budget::create([
                'type' => fake()->randomElement(['Credito', 'Contado']),
                'doctor_id' => $doctor,
                'patient_id' => $patient?->id,
                'total' => fake()->numberBetween(1000, 50000),
                'emission_date' => now(),
                'currency' => fake()->randomElement(['DOP']),
                'expiration_date' => now()->addMonth(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => $branch->id,
            ]);
            $budget->budgetdetail()->create([
                "procedure_id" => $procedure,
                "amount" => fake()->numberBetween(1000, 50000),
                "initial" => fake()->numberBetween(1000, 10000),
                "amount_of_payments" => fake()->numberBetween(1, 5),
                "total" => $budget->total,
                "treatment" => fake()->text(),
                'branch_id' => $budget->branch_id,
                "discount" => fake()->numberBetween(1, 50),
                "quantity" =>fake()->numberBetween(1, 3),
                "active" => true
            ]);
        }
    }
}
