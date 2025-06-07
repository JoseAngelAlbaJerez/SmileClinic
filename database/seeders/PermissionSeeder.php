<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //patient
            'patient.view',
            'patient.create',
            'patient.update',
            'patient.delete',

            //odontograph
            'odontograph.view',
            'odontograph.create',
            'odontograph.update',
            'odontograph.delete',
            //event
            'event.view',
            'event.create',
            'event.update',
            'event.delete',

            //Expenses
            'expenses.view',
            'expenses.create',
            'expenses.update',
            'expenses.delete',
            //CXC
            'cxc.view',
            'cxc.create',
            'cxc.update',
            'cxc.delete',
            //Drugs
            'drugs.view',
            'drugs.create',
            'drugs.update',
            'drugs.delete',
            //user
            'user.*',
            'user.create',
            'user.update',
            'user.view',
            'user.delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
