<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'complications',
        'complications_detail',
        'alergies',
        'alergies_detail',
        'drugs',
        'drugs_detail',
        'branch_id',
        'active'
    ];

    public function patient(){
        return $this->belongsTo(User::class,'patient_id','id');
    }
     public function doctor(){
        return $this->belongsTo(User::class,'doctor_id','id');
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
