<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): Response
    {
        if ($user->hasRole('receptionist')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para crear citas');
    }
    public function update(User $user): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para actualizar pacientes');
    }
    public function delete(User $user): Response
    {
        if ($user->hasRole('receptionist')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para eliminar pacientes');
    }
    public function restore(User $user): Response
    {
        if ($user->hasRole('receptionist')) {
            return Response::allow();
        }
        return Response::deny('No tiene acceso para restaurar pacientes');
    }
    public function attend(User $user, Event $event): Response
    {
        if ($user->hasRole('receptionist')) {
            return Response::allow();
        }

        if ($event->date > now()->toDateString()) {
            return Response::deny('La fecha de la cita aún no ha llegado.');
        }

        if ($event->date === now()->toDateString() && $event->starttime > now()->format('H:i:s')) {
            return Response::deny('Todavía no es hora de la cita.');
        }

        return Response::allow();
    }
}
