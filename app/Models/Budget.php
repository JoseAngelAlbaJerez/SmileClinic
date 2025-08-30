<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    protected $fillable = [
        "type",
        "active",
        "emission_date",
        "expiration_date",
        "doctor_id",
        "patient_id",
        'branch_id',
        "total",
        "currency",
        "c_x_c_id",
    ];
      protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('budgets.branch_id', $user->branch_id);
                }
            }
        });
    }
    public function budgetdetail()
    {
        return $this->hasMany(Budgetdetail::class);
    }
    public function doctor()
    {
        return $this->belongsTo(User::class);
    }
    public function CXC()
    {
        return $this->hasOne(CXC::class,'id','c_x_c_id' );
    }
     public function Insurance()
    {
        return $this->hasOne(Insurance::class,'id' );
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
