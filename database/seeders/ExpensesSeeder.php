<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 50) as $i) {

            $branch = Branch::inRandomOrder()->first();



            $user = User::inRandomOrder()
                ->first();
            DB::table('expenses')->insert([
                'description' => fake()->randomElement(['Luz','Local','Envíos','Depósito','TSS','Contabilidad','Laboratorio','Empleado','Publicidad','Teléfono','Gastos','Dra. Castro','Dra. Madelin','Dra. Odalisa','Dra. Orquídea','Dra. Paloma','Dr. Francisco']),
                'amount' => fake()->numberBetween(1000, 25000),
                'user_id' => $user->id,
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => $branch->id,
            ]);
        }
    }
}
