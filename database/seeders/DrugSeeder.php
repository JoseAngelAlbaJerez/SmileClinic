<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         foreach (range(1, 10) as $i) {

            DB::table('drugs')->insert([
                'name' => fake()->word(),
                'description' => fake()->sentence(),
                'prescription_details_id' => fake()->numberBetween(1, 10),
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
