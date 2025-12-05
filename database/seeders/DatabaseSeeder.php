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
            'first_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Jose Angel',
            'email' => 'joseangelalba24@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Cayetano Germosen',
            'email' => 'cayetanogermosen@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Cutupu',
            'email' => 'cutupu@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Dra. Castro',
            'email' => 'Castro@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Dra. Madelin',
            'email' => 'Madelin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Dra. Odalisa',
            'email' => 'Odalisa@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Dra. Paloma',
            'email' => 'Paloma@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        User::factory()->create([
            'first_name' => 'Dr. Francisco',
            'email' => 'Francisco@gmail.com',
            'password' => Hash::make('12345678'),
        ]);




       User::factory()->create([
            'first_name' => 'Juan',
        ]);



        User::factory()->create([
            'first_name' => 'Francisco',
        ]);


        User::factory()->create([
            'first_name' => 'Paloma',
        ]);




        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(medicalHistorySeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(OdontographSeeder::class);
        $this->call(ProcedureSeeder::class);
        $this->call(PrescriptionSeeder::class);
        $this->call(DrugSeeder::class);
        $this->call(BudgetSeeder::class);
        $this->call(BillSeeder::class);
        $this->call(EventSeeder::class);
    }
}
