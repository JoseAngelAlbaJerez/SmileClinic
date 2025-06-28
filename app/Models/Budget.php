<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        "type",
        "active",
        "emission_date",
        "expiration_date",
        "doctor_id",
        "patient_id",
        "total",
        "c_x_c_id",
    ];
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
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
