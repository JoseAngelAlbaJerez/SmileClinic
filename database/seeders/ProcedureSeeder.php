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
         foreach (range(1, 15) as $i) {

            DB::table('procedures')->insert([
                'name' => fake()->randomElement(['Bicuspid root canal (premolar)', 'Composite resin dental fillings (per filling)','Deep cleaning - Scaling and root planing (per quadrant)','Dental fillings - Porcelain inlay/onlay (per filling)','Dental sedation','Metallic dental crown','Molar root canal', 'Mouth guard', 'Porcelain and metal dental crown', 'Resin-base or temporary crown', 'Tooth extraction','Wisdom teeth removal' ] ),
                'coberture' => fake()->boolean(),
                'cost' => fake()->numberBetween(1000,50000),
                'active' => true,
                'created_at' => now(),
                'updated_at'=> now(),
                'branch_id' => fake()->numberBetween(1, 2),
            ]);
        }
    }
}
