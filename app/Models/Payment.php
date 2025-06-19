<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        "c_x_c_id",
        "amount_paid",
        "remaining_amount",
        "budget_detail_id",
        "total",
        "active",
        "expiration_date"
    ];
    public function CXC(){
        return $this->belongsTo(CXC::class,"c_x_c_id","id");
    }
    public function BudgetDetail(){
        return $this->belongsTo(BudgetDetail::class,"budget_detail_id","id");
    }
}
