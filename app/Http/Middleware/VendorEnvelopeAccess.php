<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class VendorEnvelopeAccess
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $arrayUrl = explode('.', (Route::currentRouteName()));
    $currentEnvelope = explode('-', $arrayUrl[1])[0];
    $id = $request->route('bid');
    $user =  Auth::user();
    $bidding = ProjectBidding::find($id);
    
    if(!$bidding){
      return response()->view('layouts.error-page', ['code' => 404, 'message' => 'PAGE NOT FOUND'], 404);
    }
    
    $allEnvelopes = [
      'eligibility' => (bool) $bidding->eligibility,
      'technical' => (bool) $bidding->technical,
      'financial' => (bool) $bidding->financial,
      'summary' => true,
    ];

    
    $envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    $projectVEndor = $bidding->vendors->where('id', $user->id)->first();
    $bidStatus = $bidding->bidVendorStatus->where('vendor_id', $user->id)->first()->complete;
    // dd($bidStatus);
    if($projectVEndor){
      if(in_array($currentEnvelope, array_keys($envelopes)) && 
      // !$bidStatus && 
      ($bidding->status == 'Bid Published' || strpos($bidding->status, 'Publication Extended') === 0)){
      return $next($request);
      }
    }
    return response()->view('layouts.error-page', ['code' => 403, 'message' => 'FORBIDDEN'], 403);
    // return $next($request);
  }
}
