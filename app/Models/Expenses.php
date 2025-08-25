<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Expenses extends Model
{
    protected $fillable =[
        "description","amount","active","user_id",'branch_id',
    ];
      protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('expenses.branch_id', $user->branch_id);
                }
            }
        });
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
