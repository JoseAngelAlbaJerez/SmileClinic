<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        "patient_id","doctor_id", "active"
    ];
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
     public function doctor(){
        return $this->belongsTo(User::class);
    }
     public function budget(){
        return $this->belongsTo(Budget::class);
    }
    public function prescriptionsDetails(){
        return $this->hasMany( PrescriptionDetail::class);
}
}
