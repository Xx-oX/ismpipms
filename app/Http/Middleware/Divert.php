<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;


/*
    main -> manage
         -> list
*/
class Divert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            return redirect()->route('login');
            //throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($authGuard->user()->can($permission)) {
                return redirect()->route('manage.view');
            }
        }
        return redirect()->route('list.view');

        throw UnauthorizedException::forPermissions($permissions);
    }
}
