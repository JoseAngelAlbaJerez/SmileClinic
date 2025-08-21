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
            //Budgets
            'budget.view',
            'budget.create',
            'budget.update',
            'budget.delete',

            //event
            'event.view',
            'event.create',
            'event.update',
            'event.delete',

            //Expenses
            'expense.view',
            'expense.create',
            'expense.update',
            'expense.delete',
            //CXC
            'cxc.view',
            'cxc.create',
            'cxc.update',
            'cxc.delete',

            //Drugs
            'drug.view',
            'drug.create',
            'drug.update',
            'drug.delete',

            //Bills
            'bill.view',
            'bill.create',
            'bill.update',
            'bill.delete',

            //Prescriptions
            'prescription.view',
            'prescription.create',
            'prescription.update',
            'prescription.delete',

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
