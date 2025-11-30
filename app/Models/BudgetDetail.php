<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
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
        static::addGlobalScope(new BranchScope);
    }
    public function procedure()
    {
        return $this->hasOne(Procedure::class, "id", 'procedure_id');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
