<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function view(User $user): Response
    {
if ($user->hasRole('receptionist') ) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para ver egresos');
    }
    public function create(User $user): Response
    {
        if ($user->hasRole('receptionist')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para crear egresos');
    }
    public function update(User $user): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para actualizar egresos');
    }
    public function delete(User $user): Response
    {
        if ($user->hasRole('receptionist')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para eliminar egresos');
    }
    public function restore(User $user): Response
    {
        if ($user->hasRole('receptionist')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para restaurar egresos');
    }
}
