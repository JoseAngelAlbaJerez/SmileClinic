<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BranchSeeder::class);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Jose Angel',
            'email' => 'joseangelalba24@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Cayetano Germosen',
            'email' => 'cayetanogermosen@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Cutupu',
            'email' => 'cutupu@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 2,
        ]);
        User::factory()->create([
            'name' => 'Dra. Castro',
            'email' => 'Castro@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Dra. Madelin',
            'email' => 'Madelin@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Dra. Odalisa',
            'email' => 'Odalisa@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Dra. Paloma',
            'email' => 'Paloma@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 2,
        ]);
        User::factory()->create([
            'name' => 'Dr. Francisco',
            'email' => 'Francisco@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 2,
        ]);

        $user = User::where('name', 'Dra. Castro')->first();
        $user->address()->create([
            'country'     => 'República Dominicana',
            'city'        => 'Santo Domingo',
            'state'       => 'Distrito Nacional',
            'street'      => 'Av. Independencia #123',
            'postal_code' => '10101',
            'branch_id'   => $user->branch_id,
        ]);

        $user = User::where('name', 'Dra. Madelin')->first();
        $user->address()->create([
            'country'     => 'República Dominicana',
            'city'        => 'Santo Domingo',
            'state'       => 'Distrito Nacional',
            'street'      => 'Calle 27 de Febrero #456',
            'postal_code' => '10102',
            'branch_id'   => $user->branch_id,
        ]);
        $user = User::where('name', 'Dra. Odalisa')->first();
        $user->address()->create([
            'country'     => 'República Dominicana',
            'city'        => 'Santo Domingo',
            'state'       => 'Distrito Nacional',
            'street'      => 'Av. Independencia #123',
            'postal_code' => '10101',
            'branch_id'   => $user->branch_id,
        ]);
        $user = User::where('name', 'Dra. Paloma')->first();
        $user->address()->create([
            'country'     => 'República Dominicana',
            'city'        => 'Santo Domingo',
            'state'       => 'Distrito Nacional',
            'street'      => 'Av. Independencia #123',
            'postal_code' => '10101',
            'branch_id'   => $user->branch_id,
        ]);
        $user = User::where('name', 'Dr. Francisco')->first();
          $user->address()->create([
            'country'     => 'República Dominicana',
            'city'        => 'Santo Domingo',
            'state'       => 'Distrito Nacional',
            'street'      => 'Calle 27 de Febrero #456',
            'postal_code' => '10102',
            'branch_id'   => $user->branch_id,
        ]);


        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(OdontographSeeder::class);
        $this->call(ProcedureSeeder::class);
        $this->call(PrescriptionSeeder::class);
        $this->call(PrescriptionDetailSeeder::class);
        $this->call(DrugSeeder::class);
        $this->call(BudgetSeeder::class);
        $this->call(EventSeeder::class);
    }
}
