<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Odontograph;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class OdontographSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 100) as $i){
        $branch = Branch::inRandomOrder()->first();
        $patient = User::role('patient')->inRandomOrder()
            ->first();
        $doctor = User::role('doctor')->inRandomOrder()->first()->id;
        $teeth = [
            "11",
            "12",
            "13",
            "14",
            "15",
            "16",
            "17",
            "18",
            "21",
            "22",
            "23",
            "24",
            "25",
            "26",
            "27",
            "28",
            "31",
            "32",
            "33",
            "34",
            "35",
            "36",
            "37",
            "38",
            "41",
            "42",
            "43",
            "44",
            "45",
            "46",
            "47",
            "48",
            "51",
            "52",
            "53",
            "54",
            "55",
            "61",
            "62",
            "63",
            "64",
            "65",
            "71",
            "72",
            "73",
            "74",
            "75",
            "81",
            "82",
            "83",
            "84",
            "85"
        ];
        $zones = ["Mesial", "Distal", "Oclusal", "Vestibular", "Lingual"];

        $procedures = [
            "Ausencia",
            "Endodoncia",
            "EndodonciaAplicada",
            "Extracción",
            "Puente",
            "Corona",
            "CoronaAplicada",
            "Implante",
            "Restauración",
            "Cariado"
        ];
        foreach ($teeth as $tooth) {
            if (fake()->boolean(20)) {
                $zone = fake()->randomElement($zones);
                $procedure = fake()->randomElement($procedures);
                $data[$tooth] = [
                    $zone => $procedure
                ];
            } else {
                $data[$tooth] = [];
            }
        }
        Odontograph::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor,
            'branch_id' => $branch->id,
            'data' => $data
        ]);
        }
    }
}
