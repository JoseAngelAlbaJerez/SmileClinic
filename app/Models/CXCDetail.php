<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CXCDetail extends Model
{
    protected $fillable=[
        "c_x_c_id",
        "total",
        "active",
    ];
    public function CXC(){
        return $this->belongsTo(CXC::class,"c_x_c_id","id");
    }
}
