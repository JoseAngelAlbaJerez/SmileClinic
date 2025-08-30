<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 10) as $i) {

             $branchId = fake()->numberBetween(1, 2);

            $patient = Patient::where('branch_id', $branchId)
                ->inRandomOrder()
                ->first();
           $doctor = User::role('doctor')->where('branch_id', $branchId)
                ->inRandomOrder()
                ->first();
            DB::table('events')->insert([
                'title' => fake()->title(),
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'attended' => fake()->boolean(),
                'starttime' => now()->format('H:i:s'),
                'endtime' => now()->addHour()->format('H:i:s'),
                'date' => now(),
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => $branchId
            ]);
        }
    }
}
