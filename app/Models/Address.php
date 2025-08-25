<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Address extends Model
{
     protected $fillable = [
        'user_id',
        'country',
        'city',
        'state',
        'street',
        'postal_code',
        'branch_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
