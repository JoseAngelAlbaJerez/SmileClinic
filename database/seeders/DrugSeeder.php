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
            $names = [
           "Azitromicina 500mg",
           "Diclofenac 50mg",
           "Diclofenac 100mg",
           "Curam 625mg",
           "Dexketoprofeno 25mg",
           "Ibuprofeno 400mg",
           "Ibuprofeno 600mg",
           "Ibuprofeno 800mg",
           "Meloxicam 7.5mg",
           "Meloxicam 15mg",
           "Diclo k biotic",
           "Rotin 90",
           "Rotin 120",
           "Dicynone"
            ];


            DB::table('drugs')->insert([
                'description' => fake()->sentence(),
                'name' => fake()->randomElement($names),
                'prescription_details_id' => fake()->numberBetween(1, 10),
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
