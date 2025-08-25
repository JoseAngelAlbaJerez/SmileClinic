<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Odontograph extends Model
{
protected $fillable = ['patient_id', 'doctor_id', 'branch_id','data'];

protected $casts = [
    'data' => 'array',
];
  protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('odontographs.branch_id', $user->branch_id);
                }
            }
        });
    }
public function patient(){
    return $this->belongsTo(Patient::class);
}
 public function doctor(){
        return $this->belongsTo(User::class,'doctor_id','id');
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
