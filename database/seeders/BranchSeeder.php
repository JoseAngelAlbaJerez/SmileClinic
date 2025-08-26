<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        Branch::create([
            'name' => 'Principal',
            'address' => 'Cayetano Germosen',
            'phone' => '809-555-1234',
            'city' => 'Cayetano Germosen',
        ]);

        Branch::create([
            'name' => 'Cutupu',
            'address' => 'Calle Norte 45',
            'phone' => '809-555-5678',
            'city' => 'Cutupu',
        ]);
    }
}
