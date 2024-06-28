<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Permit
{
    /**
     * Permits a user with the given or higher role
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @param int $role
     * @return Response
     */
    public function handle(Request $request, Closure $next, int $role): Response
    {
        if (auth()->user()->hasAccess($role))
            return $next($request);

        return back()->with('error', 'You are not authorized to perform this action');
    }
}
