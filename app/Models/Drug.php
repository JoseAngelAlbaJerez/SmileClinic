<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
   protected $fillable = [
    "name","description","active",
   ];
   public function PrescriptionDetail(){
    return $this->hasMany( PrescriptionDetail::class,"drug_id","id");
   }
}
