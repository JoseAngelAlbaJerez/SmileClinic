<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Prescription extends Model
{
    protected $fillable = [
        "patient_id","doctor_id",'branch_id', "active"
    ];
      protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('prescriptions.branch_id', $user->branch_id);
                }
            }
        });
    }
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
     public function doctor(){
        return $this->belongsTo(User::class);
    }
     public function budget(){
        return $this->belongsTo(Budget::class);
    }
    public function prescriptionsDetails(){
        return $this->hasMany( PrescriptionDetail::class);
}
 public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
