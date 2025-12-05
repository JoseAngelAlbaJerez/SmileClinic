<?php

namespace Database\Seeders;

use App\Models\Branch;
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
        Role::create(["name" => "receptionist"]);


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
        $role = Role::findByName("receptionist");
        $role->syncPermissions([
            'CXC.view',
            'CXC.create',
            'CXC.delete',
            'patient.view',
            'patient.create',
            'patient.update',
            'patient.delete',
            'odontograph.view',
            'budget.view',
            'budget.create',
            'budget.delete',
            'bill.view',
            'bill.create',
            'bill.delete',
            'event.view',
            'event.create',
            'event.update',
            'event.delete',
            'expense.view',
            'expense.create',
            'expense.delete',
            'drug.view',
            'drug.create',
            'prescription.view',
            'prescription.create',
            'prescription.delete',
        ]);

        $allBranches = Branch::pluck('id')->toArray();

        $user = User::where('first_name', 'Test User')->first();
        $user->syncRoles('admin');
        $user->branches()->sync($allBranches);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Cayetano Germosen')->first();
        $user->syncRoles('receptionist');
        $user->branches()->sync([1]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Cutupu')->first();
        $user->syncRoles('receptionist');
        $user->branches()->sync([2]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Jose Angel')->first();
        $user->syncRoles('admin');
        $user->branches()->sync($allBranches);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Dra. Castro')->first();
        $user->syncRoles('doctor');
        $user->branches()->sync([1,4]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Dra. Madelin')->first();
        $user->syncRoles('doctor','patient');
        $user->branches()->sync([2,1]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Dra. Odalisa')->first();
        $user->syncRoles('doctor');
        $user->branches()->sync([3,2]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Dra. Paloma')->first();
        $user->syncRoles('doctor');
        $user->branches()->sync([1]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Dr. Francisco')->first();
        $user->syncRoles('doctor');
        $user->branches()->sync([1,1]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Paloma')->first();
        $user->syncRoles('patient');
        $user->branches()->sync([2]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Francisco')->first();
        $user->syncRoles('patient');
        $user->branches()->sync([2]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $user = User::where('first_name', 'Juan')->first();
        $user->syncRoles('patient');
        $user->branches()->sync([1]);
        $user->active_branch_id = $user->branches()->first()->id;
        $user->save();

        $users = User::where('first_name', '!=', 'Test User')->get();
        $roles = Role::all()->pluck('first_name')->toArray();
    }
}
