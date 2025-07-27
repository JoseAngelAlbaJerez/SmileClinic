<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
     public function before (User $user): bool|null
    {
        if ($user->hasRole('admin')) {
        return true;
        }
        return null;
    }

    public function view(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para ver pacientes');
    }
       public function create(User $user): Response{
        if ($user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('staff')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para ver crear pacientes');
    }
       public function update(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para actualizar pacientes');
    }
      public function delete(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para eliminar pacientes');
    }
     public function restore(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para restaurar pacientes');
    }
}
