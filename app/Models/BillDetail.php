<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BillDetail extends Model
{
    protected $fillable = [
        "procedure_id",
        "doctor_id",
        'branch_id',
        "amount",
        "initial",
        'amount_doctor',
        'materials_amount',
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
        static::addGlobalScope(new BranchScope);
    }
    public function procedure()
    {
        return $this->hasOne(Procedure::class, "id", 'procedure_id');
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
