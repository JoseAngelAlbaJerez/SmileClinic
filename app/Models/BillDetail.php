<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
   protected $fillable = [
        "procedure_id",
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

}
