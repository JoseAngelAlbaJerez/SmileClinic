<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Patient extends Model
{
    protected $fillable = [
        "first_name",
        "last_name",
        "date_of_birth",
        "complications",
        "complications_detail",
        "alergies",
        "alergies_detail",
        "drugs",
        "drugs_detail",
        'branch_id',
        "motive",
        "address",
        "DNI",
        'ars',
        'ars_id',
        "phone_number",
        "active",
    ];
    protected static function booted()
    {
        static::addGlobalScope('branches', function ($query) {
            if ($user = Auth::user()) {
                if (!$user->hasRole('admin')) {
                    $query->where('patients.branch_id', $user->branch_id);
                }
            }
        });
    }
    public function odontographs()
    {
        return $this->hasMany(Odontograph::class, 'patient_id');
    }
    public function Event()
    {
        return $this->hasMany(Event::class, "patient_id", "id");
    }
    public function Prescriptions()
    {
        return $this->hasMany(Prescription::class, "patient_id", "id");
    }
    public function CXC()
    {
        return $this->hasOne(CXC::class, "patient_id", "id");
    }
    public function Budget()
    {
        return $this->hasMany(Budget::class, "patient_id", "id");
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
