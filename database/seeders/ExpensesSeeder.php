<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          foreach (range(1, 10) as $i) {

            $branchId = fake()->numberBetween(1, 2);


           $doctor = User::where('branch_id', $branchId)
                ->inRandomOrder()
                ->first();
            DB::table('expenses')->insert([
                'description' => fake()->sentence(),
                'amount' => fake()->numberBetween(1000,25000),
                'user_id' => $doctor->id,
                'active' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id' => fake()->numberBetween(1, 2),
            ]);
        }
    }
}
