<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class BudgetPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
      public function before(User $user): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function view(User $user): Response
    {
      return Response::allow(); }
    public function create(User $user): Response
    {
        if ($user->hasRole('doctor')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para crear presupuestos');
    }
    public function update(User $user): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para actualizar presupuestos');
    }
    public function delete(User $user): Response
    {
        if ($user->hasRole('doctor')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para eliminar presupuestos');
    }
    public function restore(User $user): Response
    {
        if ($user->hasRole('doctor')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para restaurar presupuestos');
    }
}
