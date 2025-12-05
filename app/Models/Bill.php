<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bill extends Model
{
    protected $fillable = [
        'type',
        'branch_id',
        'active',
        'emission_date',
        'expiration_date',
        'doctor_id',
        'patient_id',
        'c_x_c_id',
        'total',
        'currency',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }
    public function billdetail()
    {
        return $this->hasMany(Billdetail::class);
    }
    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id','id');
    }
    public function cxc()
    {
        return $this->belongsTo(CXC::class, 'c_x_c_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
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
