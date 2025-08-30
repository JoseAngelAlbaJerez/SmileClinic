<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BudgetDetail extends Model
{
    protected $fillable = [
        "procedure_id",
        "amount",
        "initial",
        "amount_of_payments",
        "total",
        "treatment",
        'branch_id',
        "discount",
        "quantity",
        "budget_id",
        "active"
    ];
      protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('budget_details.branch_id', $user->branch_id);
                }
            }
        });
    }
    public function procedure()
    {
        return $this->belongsTo(Procedure::class, "procedure_id", 'id');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id', 'id');
    }
    public function Payment()
    {
        return $this->hasMany(Payment::class, 'budget_detail_id', 'id');
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
