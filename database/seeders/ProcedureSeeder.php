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
         foreach (range(1, 100) as $i) {
            $cost = fake()->numberBetween(1000,50000);
            DB::table('procedures')->insert([
                'name' => fake()->randomElement(['Endodoncia de premolares', 'Obturaciones de resina compuesta (por obturación)', 'Prótesis móviles', 'Limpieza profunda: raspado y alisado radicular (por cuadrante)', 'Obturaciones de porcelana (inlay/onlay) y de porcelana (por obturación)', 'Sedación dental, Corona metálica', 'Endodoncia de molares', 'Protector bucal', 'Corona de porcelana y metal', 'Corona provisional o de resina', 'Extracción dental', 'Extracción de muelas del juicio.'] ),
                'coverage' => fake()->boolean(),
                'cost' => $cost,
                'insuranced_amount' => $cost * 0.8,
                'active' => true,
                'created_at' => now(),
                'updated_at'=> now(),
            ]);
        }
    }
}
