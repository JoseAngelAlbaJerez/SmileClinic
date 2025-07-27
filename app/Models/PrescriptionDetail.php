<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
    protected $fillable = [
        "description",
        "fc",
        "time_interval",
        "prescription_id",
        "drug_id",
        "ending_date",
        "active"
    ];
    public function drugs()
    {
        return $this->belongsTo(Drug::class, 'drug_id');
    }
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
