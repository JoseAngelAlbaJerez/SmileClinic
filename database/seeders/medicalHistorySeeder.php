<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\MedicalHistory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class medicalHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('patient')->get();
        foreach ($users as $i) {

            $branchId = Branch::inRandomOrder()->first()->id;
            $complications = fake()->boolean();
            $alergies = fake()->boolean();
            $drugs = fake()->boolean();

            MedicalHistory::create([
                'patient_id' => $i->id,
                'doctor_id' => User::role('doctor')->inRandomOrder()->first()->id,
                'complications' => $complications,
                'complications_detail' => $complications ? fake()->sentence() : '',
                'alergies' => $alergies,
                'alergies_detail' => $alergies ? fake()->word() : '',
                'drugs' => $drugs,
                'drugs_detail' => $drugs ? fake()->word() : '',
                'branch_id' => $branchId
            ]);
        }
    }
}
