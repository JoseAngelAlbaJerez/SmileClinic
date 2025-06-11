<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         foreach (range(1, 10) as $i) {

            DB::table('procedures')->insert([
                'name' => fake()->sentence(),
                'coberture' => fake()->boolean(),
                'cost' => fake()->numberBetween(1000,50000),
                'active' => true,
                'created_at' => now(),
                'updated_at'=> now(),
            ]);
        }
    }
}
