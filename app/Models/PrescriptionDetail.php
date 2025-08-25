<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PrescriptionDetail extends Model
{
    protected $fillable = [
        "description",
        "prescription_id",
        "drug_id",
        'branch_id',
        "active"
    ];
      protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('branch_id', $user->branch_id);
                }
            }
        });
    }
    public function drugs()
    {
        return $this->belongsTo(Drug::class, 'drug_id');
    }
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
