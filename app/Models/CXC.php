<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CXC extends Model
{
    protected $fillable = [
        "patient_id",
        "doctor_id",
        "balance",
        "active",
        'branch_id',
    ];
    public function Budget()
    {
        return $this->hasMany(Budget::class,'c_x_c_id','id');
    }
    public function CXCDetail(){
        return $this->hasMany(CXCDetail::class,'c_x_c_id','id');
    }
    public function Patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function Payment()
    {
        return $this->hasMany(Payment::class);
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
