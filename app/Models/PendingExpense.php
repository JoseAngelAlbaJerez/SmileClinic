<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'user_id',
        'branch_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class);
    }



    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
