<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    protected $fillable = [

        'title',
        'attended_at',
        'doctor_id',
        'branch_id',
        'patient_id',
        'attended',
        'starttime',
        'endtime',
        'date',
        'active',
        'google_event_id'

    ];
     protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('events.branch_id', $user->branch_id);
                }
            }
        });
    }

    public function doctor(){
        return $this->belongsTo(User::class);
    }
     public function patient(){
        return $this->belongsTo(Patient::class);
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
