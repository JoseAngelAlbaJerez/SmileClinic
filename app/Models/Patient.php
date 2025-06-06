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
        // return $this->hasMany(Odontograph::class,'id');
    }
     public function CXC(){
        // return $this->belongsTo(CXC::class,"","id");
    }
    public function Budget(){
        // return $this->hasMany(Budget::class,"","id");
    }
}
