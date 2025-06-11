<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetDetail extends Model
{
   protected $fillable=["procedure_id","amount","total","treatment","discount","quantity","budget_id","active"];
  public function procedure(){
    return $this->belongsTo(Procedure::class,"procedure_id",'id');
}

    public function budget(){
        return $this->belongsTo(Budget::class,'budget_id','id');
    }
}
