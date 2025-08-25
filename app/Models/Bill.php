<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

   ];
   public function billdetail()
    {
        return $this->hasMany(Billdetail::class);
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
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
