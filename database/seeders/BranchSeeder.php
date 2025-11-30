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
            'phone_number' => '809-555-1234',

        ]);

        Branch::create([
            'name' => 'Cutupu',
            'address' => 'Calle Norte 45',
            'phone_number' => '809-523-6587',
        ]);
         Branch::create([
            'name' => 'Santiago',
            'address' => 'Cerca de el museo',
            'phone_number' => '809-786-6531',
        ]);
         Branch::create([
            'name' => 'Moca',
            'address' => 'Frente al Antonio de la Maza',
            'phone_number' => '809-675-1237',
        ]);

    }
}
