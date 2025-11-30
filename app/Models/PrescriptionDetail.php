<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PrescriptionDetail extends Model
{
    protected $fillable = [
        "description",
        "prescription_id",
        "drug_id",
        'branch_id',
        "active"
    ];
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }
    public function drugs()
    {
        return $this->hasOne(Drug::class, 'id','drug_id');
    }
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
