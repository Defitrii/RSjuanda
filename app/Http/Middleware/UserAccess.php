<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$userTypes)
    {
        $user = auth()->user();

        if (!in_array($user->type, $userTypes)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
