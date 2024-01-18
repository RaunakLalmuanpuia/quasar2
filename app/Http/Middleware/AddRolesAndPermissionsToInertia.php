<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

class AddRolesAndPermissionsToInertia
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (auth()->check()) {
      Inertia::share([
        'user' => [
          'roles' => auth()->user()->roles->pluck('name'),
          'permissions' => auth()->user()->getAllPermissions()->pluck('name'),
        ],
      ]);
    }
    return $next($request);
  }
}
