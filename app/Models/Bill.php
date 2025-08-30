<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bill extends Model
{
    protected $fillable =[
    'type',
    'branch_id',
    'active',
    'emission_date',
    'expiration_date',
    'doctor_id',
    'patient_id',
    'c_x_c_id',
    'total',
    'material_provider',
    'amount_doctor',
    'currency',
    'doctor_id',
    'materials_amount'
   ];
     protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('bills.branch_id', $user->branch_id);
                }
            }
        });
    }
   public function billdetail()
    {
        return $this->hasMany(Billdetail::class);
    }
    public function doctor()
    {
        return $this->belongsTo(User::class);
    }
   public function cxc()
{
    return $this->belongsTo(CXC::class, 'c_x_c_id');
}

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
