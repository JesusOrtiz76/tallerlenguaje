<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckNameChange
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->ochange_name) {
            session()->flash('show_name_modal', true);
        }

        return $next($request);
    }
}
