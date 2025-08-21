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
           "Dicynone",
           "Pregabalina 75 Mg",
           "Celecoxib 200 Mg",
           "Diclofenac Potasico 75 Mg",
           "Diclofenac Potasico 100 Mg",
           "Diclofenac 75 Mg, Paracetamol 300 Mg, Clorzoxazona 250 Mg",
           "Diclofenac 50 g (gel)",
           "Meloxicam 15 Mg (gel)",
           "Ciprofloxacina 500 Mg",
           "Ciprofloxacina 500 Mg + Fenazopiridina 100 Mg",
           "Pantoprazol 40 Mg",
           "Paracetamol 500Mg",
           "Celecoxib 400 Mg",
           "Ibuprofeno 400 Mg + Cafeina 50 Mg",
           "Diclofenac 75 Mg, Vitamina B1, B6, B12",
           "Diclofenac Potasico 100 Mg",
           "Etoricoxib 90 Mg",
           "Ketorolaco 10 Mg",
           "Ciprofioxacina 750 Mg",
           "Levofloxacina 500 Mg",
           "Sulfadiazina de Plata 1%",
           "Paracetamol 750 Mg",
           "Dexketoprofeno 25 Mg",
           "Nitazoxanida 500 Mg",
           "Diclofenac 75 Mg, Vitamina B1, B6, B12, Clorzoxazona 250 Mg",
           "Diclofenac Potasico 12.5 Mg",
           "Etoricoxib 120 Mg",
           "Ketorolaco 20 Mg",
           "Ciprofloxacina 1000 Mg",
           "Levofloxacina 750 Mg",
           "Levocetirizina 5 Mg",
            ];


            DB::table('drugs')->insert([
                'description' => fake()->sentence(),
                'name' => fake()->randomElement($names),
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
