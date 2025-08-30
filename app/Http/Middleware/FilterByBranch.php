<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class FilterByBranch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // FiltrarPorSucursal.php
  public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            $branchId = null;
        } elseif ($user->hasAnyRole(['admin', 'owner'])) {
            $branchId = 'all';
        } else {
            $branchId = $user->branch_id;
        }

        view()->share('sucursal_id', $branchId);

        Inertia::share('sucursal_id', $branchId);

        return $next($request);
    }
}
