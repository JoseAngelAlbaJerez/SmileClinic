<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
   protected $fillable = [
    "name","description","active",'branch_id',
   ];
   public function PrescriptionDetail(){
    return $this->hasMany( PrescriptionDetail::class,"drug_id","id");
   }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
