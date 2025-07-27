<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        "first_name",
        "last_name",
        "date_of_birth",
        "complications",
        "complications_detail",
        "alergies",
        "alergies_detail",
        "drugs",
        "drugs_detail",
        "motive",
        "address",
        "DNI",
        'ars',
        "phone_number",
        "active",
    ];
    public function odontographs(){
        return $this->hasMany(Odontograph::class,'patient_id');
    }
     public function Event(){
         return $this->hasMany(Event::class,"patient_id","id");
    }
      public function Prescriptions(){
         return $this->hasMany(Prescription::class,"patient_id","id");
    }
     public function CXC(){
       return $this->hasOne(CXC::class,"patient_id","id");
    }
    public function Budget(){
        return $this->hasMany(Budget::class,"patient_id","id");
    }
}
