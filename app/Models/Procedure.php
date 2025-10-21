<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Procedure extends Model
{
    protected $fillable = ["name", "coverage",'branch_id', "cost", "active","insuranced_amount"];

      protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('procedures.branch_id', $user->branch_id);
                }
            }
        });
    }
    public function BudgetDetail(){
        return $this->hasMany( BudgetDetail::class);
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
