<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Drug extends Model
{
   protected $fillable = [
    "name","description","active",'branch_id',
   ];
     protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('drugs.branch_id', $user->branch_id);
                }
            }
        });
    }
   public function PrescriptionDetail(){
    return $this->hasMany( PrescriptionDetail::class,"drug_id","id");
   }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
