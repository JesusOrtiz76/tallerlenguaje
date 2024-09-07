<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $rol)
    {
        if (!Auth::check())
            return redirect('login');

        $user = Auth::user();
        if ($user->orol != $rol) {
            // Redireccionar al usuario si no tiene el rol necesario
            return redirect('home')->with('warning', 'No tienes permiso para acceder a esta área');
        }

        return $next($request);
    }
}
