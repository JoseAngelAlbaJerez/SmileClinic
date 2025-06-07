<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odontograph extends Model
{
    protected $casts = [
    'cavities' => 'array',
    'extraction' => 'array',
    'crown' => 'array',
    'fracture' => 'array',
    'restoration' => 'array',
    'braces' => 'array',
    'implant' => 'array',
    'bridge' => 'array',
    'dentures' => 'array',
    'filter' => 'boolean',
    'missing' => 'boolean',
    'root_canal' => 'boolean',
    'cleaning' => 'boolean',
    'active' => 'boolean',
];
protected $fillable = [
     'cavities',
     'patient_id',
    'extraction',
    'crown',
    'fracture',
    'restoration',
    'braces',
    'implant',
    'bridge',
    'dentures',
    'filter',
    'missing',
    'root_canal',
    'cleaning',
    'active',
    'doctor_id'
];
public function patient(){
    return $this->belongsTo(Patient::class);
}
 public function doctor(){
        return $this->belongsTo(User::class,'doctor_id','id');
    }
}
