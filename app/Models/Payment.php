<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    protected $fillable = [
        "c_x_c_id",
        "amount_paid",
        "remaining_amount",
        "budget_detail_id",
        "total",
        "active",
        'branch_id',
        "expiration_date"
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
    public function CXC(){
        return $this->belongsTo(CXC::class,"c_x_c_id","id");
    }
    public function BudgetDetail(){
        return $this->belongsTo(BudgetDetail::class,"budget_detail_id","id");
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
