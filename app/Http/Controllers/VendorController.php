<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\VendorRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\VendorResource;
use Illuminate\Validation\Rules\Password;

class VendorController extends Controller
{
  public function index()
  {
    return User::with('vendorContacts')->get();
    // return VendorResource::collection(User::all());
  }

  public function store(Request $request)
  {
    
    $data = $request->validate([
      'email' => 'required|email|unique:users,email',
      'crtd_user' => 'required',
    ]);
    $data['token'] = Str::random(40);
    // return $data;
    $vendor = User::create($data);

    // $vendorEmail = User::find($vendor->id);
    
    Mail::to($vendor->email)->send(new VendorRegistration($vendor));

    return 'success';
  }

  public function registration(Request $request)
  {
    $vendor = User::where('email', $request->email)->first();

    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
      'address' => ['required'],
      'number' => ['required'],
      'password' => ['required', 'string', 'confirmed', Password::defaults()],
    ]);
    $validated['token'] = null;

    $vendor->update($validated);
  
    // event(new Registered($user));

    Auth::login($vendor);

    return redirect()->route('dashboard');

  }
}
