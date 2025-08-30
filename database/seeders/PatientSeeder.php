<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 20) as $i) {
            $complications = fake()->boolean();
            $alergies = fake()->boolean();
            $drugs = fake()->boolean();

            DB::table('patients')->insert([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'ars' => fake()->randomElement(['Humano', 'Monumental', 'Universal', 'Senasa']),
                'complications' => $complications,
                'complications_detail' => $complications ? fake()->sentence() : '',
                'alergies' => $alergies,
                'alergies_detail' => $alergies ? fake()->word() : '',
                'drugs' => $drugs,
                'drugs_detail' => $drugs ? fake()->word() : '',
                'motive' => fake()->sentence(3),
                'address' => fake()->address(),
                'DNI' => fake()->unique()->numerify('########'),
                'phone_number' => fake()->numerify('809#######'),
                'date_of_birth' => fake()->date('Y-m-d', '2008-01-01'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => fake()->numberBetween(1, 2),
            ]);
        }
    }
}
