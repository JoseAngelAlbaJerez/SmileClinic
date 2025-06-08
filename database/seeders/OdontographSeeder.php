<?php

namespace Database\Seeders;

use App\Models\Odontograph;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class OdontographSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::pluck('id');


        foreach ($patients as $patientId) {
            Odontograph::create([
                'patient_id'     => $patientId,
                'doctor_id' => User::role('doctor')->inRandomOrder()->first()->id,
                'cavities'       => json_encode([
                    'center' => rand(0, 1),
                    'top'    => rand(0, 1),
                    'bottom' => rand(0, 1),
                    'left'   => rand(0, 1),
                    'right'  => rand(0, 1),
                ]),
                'missing'        => (bool)rand(0, 1),
                'filter'         => (bool)rand(0, 1),
                'root_canal'     => (bool)rand(0, 1),
                'cleaning'       => (bool)rand(0, 1),
                'active'         => (bool)rand(0, 1),
                'veener'         => now()->subDays(rand(0, 100)),
                'graft'          => now()->subDays(rand(0, 100)),
                'sealant'        => now()->subDays(rand(0, 100)),
                'bonding'        => now()->subDays(rand(0, 100)),
                'examinations'   => now()->subDays(rand(0, 100)),
                'fissure_seal'   => now()->subDays(rand(0, 100)),
            ]);
        }
    }
}
