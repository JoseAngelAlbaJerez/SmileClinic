<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
 protected $fillable =[
    'ars',
    'budget_id',
    'patient_id',
    'branch_id',
    'affiliate_signature',
    'reclaimer_signature'


 ];

 public function budget(){
    $this->belongsTo(Budget::class, 'budget_id','id');
 }
}
