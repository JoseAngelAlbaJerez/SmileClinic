<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable =[
        "description","amount","active","user_id",'branch_id',
    ];
  public function user()
{
    return $this->belongsTo(User::class);
}
 public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
