<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Drug extends Model
{
    protected $fillable = [
        "name",
        "description",
        "active",
        'branch_id',
    ];

    public function PrescriptionDetail()
    {
        return $this->belongsTo(PrescriptionDetail::class, "drug_id", "id");
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
