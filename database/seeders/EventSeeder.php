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
        foreach (range(1, 100) as $i) {

            $branchId = fake()->numberBetween(1, 2);

            $patient = User::role('patient')->inRandomOrder()
                ->first();
            $doctor = User::role('doctor')
                ->inRandomOrder()
                ->first();
            $start = fake()->dateTimeBetween('today 08:00', 'today 17:00');
            $end = (clone $start)->modify('+' . fake()->numberBetween(30, 90) . ' minutes');

            DB::table('events')->insert([
                'title' => fake()->sentence(3),
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'attended' => fake()->boolean(),
                'starttime' => $start->format('H:i:s'),
                'endtime' => $end->format('H:i:s'),
                'date' => $start->format('Y-m-d'),
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => $branchId
            ]);
        }
    }
}
