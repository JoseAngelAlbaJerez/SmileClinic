<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Procedure;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 100) as $i) {

            $branch = Branch::inRandomOrder()->first();
            $patient = User::role('patient')->inRandomOrder()->first();
            $doctor = User::role('doctor')->inRandomOrder()->first()->id;
            $procedure = Procedure::inRandomOrder()->first()->id;
            if ($patient->CXC()->exists()) {
                $CXC = $patient->CXC()->first();
            } else {
                $CXC = $patient->CXC()->create([
                    'doctor_id' => $doctor,
                    'balance' => 0,
                    'active' => true,
                    'branch_id' => $branch->id
                ]);
            }


            $bill = \App\Models\Bill::create([
                'type' => fake()->randomElement(['Credito', 'Contado']),
                'doctor_id' => $doctor,
                'patient_id' => $patient?->id,
                'c_x_c_id' => $CXC->id,
                'total' => fake()->numberBetween(1000, 50000),
                'emission_date' => now(),
                'currency' => fake()->randomElement(['DOP']),
                'expiration_date' => now()->addMonth(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => $branch->id,
            ]);
            $CXC->balance = $bill->total;
            $CXC->save();

            $bill->billdetail()->create([
                "procedure_id" => $procedure,
                "doctor_id" => $doctor,
                'branch_id' => $bill->branch_id,
                "amount" => fake()->numberBetween(1000, 10000),
                "initial" =>fake()->numberBetween(1000, 10000) ,
                'amount_doctor' => fake()->numberBetween(1000, 10000),
                'materials_amount' => fake()->numberBetween(1000, 10000),
                "amount_of_payments" => fake()->numberBetween(1,4),
                "total" => $bill->total,
                "treatment" => fake()->text(),
                "discount" => fake()->numberBetween(1, 50),
                "quantity" => fake()->numberBetween(1, 10),
                "active" => true
            ]);
        }
    }
}
