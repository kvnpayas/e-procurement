<?php

namespace App\Http\Middleware;

use App\Models\UserOtp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OtpAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $otpRecord = UserOtp::where('user_id', Auth::user()->id)->first();
      if($otpRecord && !$request->is('otp')){
        return redirect('/otp');
      }

      if(!$otpRecord && $request->is('otp')){
        return redirect(route('dashboard'));
      }
      return $next($request);
     
    }
}
