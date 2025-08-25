<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FilterByBranch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   // FiltrarPorSucursal.php
public function handle($request, Closure $next)
{
    $user = Auth::user();

    if (!$user) {
        view()->share('sucursal_id', null);
    } elseif (in_array($user->roles[0], ['admin', 'owner'])) {
        view()->share('sucursal_id', 'all');
    } else {
        view()->share('sucursal_id', $user->branch_id);
    }

    return $next($request);
}


}
