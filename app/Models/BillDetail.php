<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BillDetail extends Model
{
   protected $fillable = [
        "procedure_id",
        'branch_id',
        "amount",
        "initial",
        "amount_of_payments",
        "total",
        "treatment",
        "discount",
        "quantity",
        "bill_id",
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
    public function procedure()
    {
        return $this->belongsTo(Procedure::class, "procedure_id", 'id');
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
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
