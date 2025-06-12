<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CXC extends Model
{
    protected $fillable = [
        "budget_id",
        "patient_id",
        "doctor_id",
        "balance",
        "active"
    ];
    public function Budget(){
        return $this->belongsTo(Budget::class);
    }
      public function Patient(){
        return $this->belongsTo(Patient::class);
    }
    public function Payment(){
        return $this->hasMany(Payment::class);
    }
}
