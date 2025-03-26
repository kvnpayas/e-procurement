<?php

use App\Models\Menu;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Http;

// function apiToken()
// {

//   $response = Http::asForm()->post(imsUrl() . 'oauth/token', [
//     'grant_type' => 'client_credentials',
//     'client_id' => '1',
//     'client_secret' => 'Fn6MClaxscoVkPCzqAus09C9Kfq4Z9s8yV9SHnzB',
//     'scope' => '',
//   ]);

//   return $response->json()['access_token'];
// }

if (!function_exists('apiTokenBC')) {
  function apiTokenBC()
  {

    $response = Http::withHeaders([
      'Accept' => 'application/json'
    ])->post(bcToken() . 'ApiToken/token', ['clientId' => '2', 'clientSecret' => '873438a5-b11c-4dab-9f3e-be61eda2a067']);

    if ($response->successful()) {
      $token = $response->json()['access_token'];
      return 'Bearer ' . $token;
    } else {
      return 'Invalid Credentials';
    }
  }
}

if (!function_exists('apiTokenBC') || !function_exists('bcToken') || !function_exists('BCUrl') || !function_exists('projectBid')) {
  function imsUrl()
  {
    return 'http://ims.tei/';
  }

  function bcToken()
  {
    return 'http://tei-bridge:8080/api/';
  }

  function BCUrl()
  {
    return 'http://tei-bridge:8082/api/';
  }

  function projectBid($id)
  {
    return ProjectBidding::findorFail($id);
  }

  // Access Right per module
  function roleAccessRights($buttonFunc)
  {

    $role = Auth::user()->role;
    $mainRoute = explode('.', session('originalRouteName'))[0];
    $menu = $role->menus->where('route_name', $mainRoute)->first();
    // Return false if no menu is found for the route
    if (!$menu) {
      return false;
    }
    
    if (is_array($buttonFunc)) {
      foreach ($buttonFunc as $func) {
        if ($menu->pivot->{$func} ?? false) { // Use null coalescing operator to handle missing pivot properties
          return true;
        }
      }
      return false; // Return false if none of the array items are true
    } else {
      return $menu->pivot->{$buttonFunc} ?? false; // Use null coalescing operator for single string
    }
  }
}
