<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessRights
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $user = $request->user();
    $route = Route::currentRouteName();
    $currentRoute = explode('.', $route)[0];
    $userRoutes = $user->role->menus->pluck('route_name')->toArray();

    if (in_array($currentRoute, $userRoutes)) {
      return $next($request);
    }
    return response()->view('layouts.error-page', ['code' => 403, 'message' => 'FORBIDDEN'], 403);
  }
}
