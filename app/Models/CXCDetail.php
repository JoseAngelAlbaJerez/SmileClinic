<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CXCDetail extends Model
{
    protected $fillable=[
        "c_x_c_id",
        "total",
        'branch_id',
        "active",
    ];
      protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('c_x_c_s.branch_id', $user->branch_id);
                }
            }
        });
    }    public function CXC(){
        return $this->belongsTo(CXC::class,"c_x_c_id","id");
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
