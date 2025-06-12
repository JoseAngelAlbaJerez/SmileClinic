<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        "cxc_id",
        "amount_paid",
        "remaining_amount",
        "budget_detail_id",
        "total",
        "active",
    ];
    public function CXC(){
        return $this->belongsTo(CXC::class,"cxc_id","id");
    }
    public function BudgetDetail(){
        return $this->belongsTo(BudgetDetail::class,"budget_detail_id","id");
    }
}
