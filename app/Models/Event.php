<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [

        'title',
        'doctor_id',
        'patient_id',
        'attended',
        'starttime',
        'endtime',
        'date',
        'active',
        'google_event_id'

    ];
    public function doctor(){
        return $this->belongsTo(User::class);
    }
     public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
