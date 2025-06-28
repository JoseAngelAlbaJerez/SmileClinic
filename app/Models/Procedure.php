<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = ["name", "coberture", "cost", "active"];

    public function BudgetDetail(){
        return $this->hasMany( BudgetDetail::class);
    }
}
