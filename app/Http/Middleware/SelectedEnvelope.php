<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class SelectedEnvelope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $arrayUrl = explode('.', (Route::currentRouteName()));
      $envelope = explode('-', $arrayUrl[1])[0];
      $id = $request->route('biddingId');
      $bidding = ProjectBidding::find($id);
      if($bidding->{$envelope}){
        return $next($request);
      }else{
        return response()->view('layouts.error-page', ['code' => 403, 'message' => 'FORBIDDEN'], 403);
      }
    }
}
