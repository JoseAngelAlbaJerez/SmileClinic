<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Prescription extends Model
{
    protected $fillable = [
        "patient_id",
        "doctor_id",
        'branch_id',
        "active"
    ];
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }
    public function patient()
    {
        return $this->belongsTo(User::class,'patient_id','id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id','id');
    }
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
    public function prescriptionsDetails()
    {
        return $this->hasMany(PrescriptionDetail::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
