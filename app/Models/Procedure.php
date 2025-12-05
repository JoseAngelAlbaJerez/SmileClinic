<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Procedure extends Model
{
    protected $fillable = ["name", "coverage", "cost", "active", "insuranced_amount"];


    public function BudgetDetail()
    {
        return $this->belongsTo(BudgetDetail::class,'procedure_id');
    }

}
