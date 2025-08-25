<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CXCDetail extends Model
{
    protected $fillable=[
        "c_x_c_id",
        "total",
        'branch_id',
        "active",
    ];
    public function CXC(){
        return $this->belongsTo(CXC::class,"c_x_c_id","id");
    }
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
