<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
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
        static::addGlobalScope(new BranchScope);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class,'patient_id','id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
