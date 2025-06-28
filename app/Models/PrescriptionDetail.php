<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
     protected $fillable = [
        "description","fc","time_interval","prescription_id","active"
    ];
}
