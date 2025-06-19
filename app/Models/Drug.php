<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
   protected $fillable = [
    "name","description","prescription_details_id","active",
   ];
   public function PrescriptionDetail(){
    return $this->belongsTo( PrescriptionDetail::class,"prescription_details_id","id");
   }
}
