<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
        return Response::deny('No tiene acceso para ver usuarios');
    }
       public function create(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para ver crear usuarios');
    }
       public function update(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para actualizar usuarios');
    }
      public function delete(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para eliminar usuarios');
    }
     public function restore(User $user): Response{
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para restaurar usuarios');
    }
}
