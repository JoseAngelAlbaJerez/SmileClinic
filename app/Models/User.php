<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'DNI',
        'specialty',
        'phone_number',
        'date_of_birth',
        'address',
        'email',
        'avatar',
        'password',
        'active',
        'active_branch_id'
    ];


    public function medicalHistoriesAsPatient()
    {
        return $this->hasOne(MedicalHistory::class, 'patient_id', 'id');
    }

    public function medicalHistoriesAsDoctor()
    {
        return $this->hasMany(MedicalHistory::class, 'doctor_id', 'id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'users_branches', 'user_id', 'branch_id');
    }

    public function odontographs()
    {
        return $this->hasMany(Odontograph::class, 'patient_id');
    }
    public function Event()
    {
        return $this->hasMany(Event::class, "patient_id", "id");
    }
    public function bill()
    {
        return $this->hasMany(Bill::class, "patient_id", "id");
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


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'date_of_birth' => 'date',
        ];
    }
}
