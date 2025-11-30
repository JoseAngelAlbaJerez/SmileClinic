<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    protected $fillable = [
        "type",
        "active",
        "emission_date",
        "expiration_date",
        "doctor_id",
        "patient_id",
        'branch_id',
        "total",
        "currency",
        "c_x_c_id",
    ];
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }
    public function budgetdetail()
    {
        return $this->hasMany(Budgetdetail::class);
    }
    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id','id');
    }

    public function Insurance()
    {
        return $this->hasOne(Insurance::class, 'id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
