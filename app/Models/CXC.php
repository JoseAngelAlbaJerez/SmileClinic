<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CXC extends Model
{
    protected $fillable = [
        "patient_id",
        "doctor_id",
        "balance",
        "active",
        'branch_id',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }
    public function bills()
    {
        return $this->hasMany(Bill::class, 'c_x_c_id');
    }


    public function patient()
    {
        return $this->belongsTo(User::class,'patient_id','id');
    }
    public function Payment()
    {
        return $this->hasMany(Payment::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
