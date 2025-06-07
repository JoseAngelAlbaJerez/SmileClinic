<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          foreach (range(1, 10) as $i) {

            DB::table('expenses')->insert([
                'description' => fake()->sentence(),
                'amount' => fake()->numberBetween(1000,25000),
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
