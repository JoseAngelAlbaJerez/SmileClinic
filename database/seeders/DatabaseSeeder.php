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
        User::factory()
            ->has(Address::factory()->count(1))
            ->count(10)
            ->create();
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
            'email' => 'sanpedro@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Cutupu',
            'email' => 'Mario@gmail.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 2,
        ]);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(OdontographSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(BudgetSeeder::class);
        $this->call(ProcedureSeeder::class);
        $this->call(PrescriptionSeeder::class);
        $this->call(PrescriptionDetailSeeder::class);
        $this->call(DrugSeeder::class);
    }
}
