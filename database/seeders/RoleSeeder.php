<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(["name" => "admin"]);
        Role::create(["name" => "doctor"]);
        Role::create(["name" => "patient"]);
        Role::create(["name" => "staff"]);


        $role = Role::findByName("admin");
        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        $role = Role::findByName("doctor");
        $role->syncPermissions([
            'odontograph.view',
            'odontograph.create',
            'odontograph.update',
            'odontograph.delete',
            'patient.view',
            'patient.create',
            'event.view',
            'event.create',
            'event.update',
            'event.delete',
            'prescription.view',
            'prescription.create',
            'prescription.update',
            'prescription.delete',
        ]);

        $role = Role::findByName("patient");
        $role->syncPermissions([
            'odontograph.view',
            'patient.view',
            'prescription.view',
        ]);
        $role = Role::findByName("staff");
        $role->syncPermissions([
            'patient.view',
            'patient.create',
            'patient.update',
            'patient.delete',
            'odontograph.view',
            'budget.view',
            'budget.create',
            'budget.update',
            'budget.delete',
            'bill.view',
            'bill.create',
            'bill.update',
            'bill.delete',
            'event.view',
            'event.create',
            'event.update',
            'event.delete',
            'expense.view',
            'expense.create',
            'expense.update',
            'expense.delete',
        ]);

        $user = User::where('name', 'Test User')->first();
        $user->syncRoles('admin');

        $user = User::where('name', 'San Pedro')->first();
        $user->syncRoles('staff');

        $user = User::where('name', 'Mario')->first();
        $user->syncRoles('patient');

        $user = User::where('name', 'Jose Angel')->first();
        $user->syncRoles('doctor');

        $users = User::where('name', '!=', 'Test User')->get();
        $roles = Role::all()->pluck('name')->toArray();
        foreach ($users as $user) {
            $randrole = $roles[array_rand($roles)];
            $user->assignRole($randrole);
        }
    }
}
