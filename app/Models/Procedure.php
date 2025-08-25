<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = ["name", "coberture",'branch_id', "cost", "active"];

    public function BudgetDetail(){
        return $this->hasMany( BudgetDetail::class);
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
