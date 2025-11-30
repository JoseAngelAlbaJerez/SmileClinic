<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Expenses extends Model
{
    protected $fillable = [
        "description",
        "amount",
        "active",
        "user_id",
        'branch_id',
        "doctor_id"
    ];
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
