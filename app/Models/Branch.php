<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'active',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'branch_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'branch_id');
    }

    public function billDetails()
    {
        return $this->hasMany(BillDetail::class, 'branch_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'branch_id');
    }

    public function budgetDetails()
    {
        return $this->hasMany(BudgetDetail::class, 'branch_id');
    }

    public function cxc()
    {
        return $this->hasMany(CXC::class, 'branch_id');
    }



    public function drugs()
    {
        return $this->hasMany(Drug::class, 'branch_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'branch_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expenses::class, 'branch_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'branch_id');
    }

    public function odontographs()
    {
        return $this->hasMany(Odontograph::class, 'branch_id');
    }



    public function payments()
    {
        return $this->hasMany(Payment::class, 'branch_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'branch_id');
    }
      public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class, 'branch_id');
    }

    public function prescriptionDetails()
    {
        return $this->hasMany(PrescriptionDetail::class, 'branch_id');
    }



    public function users()
    {
        return $this->belongsToMany(User::class, 'users_branches', 'branch_id', 'user_id');
    }
}
