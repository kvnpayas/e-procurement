<?php

namespace App\Livewire;

use App\Mail\OtpMail;
use App\Models\UserOtp;
use Livewire\Component;
use App\Helpers\LogsActivity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OtpPage extends Component
{
  public $otpUser, $expiresAt;
  public $otpInputs = [], $autoFocus, $disableButton = true, $otpCode;
  public $resendTime;

  public function mount()
  {
    $this->otpUser = UserOtp::where('user_id', Auth::user()->id)->first();
    $parseDate = Carbon::parse($this->otpUser->expires_at);
    $now = Carbon::now();

    // Get the difference in seconds
    $remainingTime = $now->diffInSeconds($parseDate, false);
    if ($remainingTime < 0) {
      $this->expiresAt = "Time has passed.";
    } else {
      // Convert the difference into hours, minutes, and seconds
      $minutes = floor(($remainingTime % 3600) / 60);
      $seconds = $remainingTime % 60;

      $this->expiresAt = "OTP code expires at: {$minutes} minutes, {$seconds} seconds.";
    }

    $this->autoFocus = 0;
    for ($i = 0; $i < 6; $i++) {
      $this->otpInputs[$i] = null;
    }
  }
  public function updatedOtpInputs($value, $field)
  {
    // $index = explode('.', $field)[0];
    // if (is_numeric($value)) {
    //   $this->autoFocus = $index + 1;
    //   $this->dispatch('focus', $this->autoFocus);
    // } else {
    //   $this->otpInputs[$index] = null;
    //   // if ($value == null) {
    //   //   $this->autoFocus = $index - 1;
    //   //   $this->dispatch('focus', $this->autoFocus);
    //   // } else {
    //   //   $this->otpInputs[$index] = null;
    //   // }
    // }

    $this->disableButton = in_array(null, $this->otpInputs, true);
  }

  public function submitOtp()
  {
    if (in_array(null, $this->otpInputs, true)) {
      $this->otpCode = null;
    } else {
      $this->otpCode = implode('', $this->otpInputs);
    }

    $this->validate([
      'otpCode' => 'required|size:6|in:' . $this->otpUser->otp
    ], [
      'otpCode.in' => 'The input otp code is invalid.'
    ]);

    $this->otpUser->delete();

    Session::regenerate();
    LogsActivity::loggedIn();
    // $this->redirect(route('dashboard', absolute: false), navigate: true);
    return redirect()->route('dashboard');
  }
  public function resendModal()
  {
    $resend = Carbon::parse($this->otpUser->expires_at)->subMinutes(value: 3);
    $timeRemaining = now()->diffInSeconds($resend, false);
    $minutes = floor(($timeRemaining % 3600) / 60);
    $seconds = $timeRemaining % 60;
    if ($timeRemaining < 0) {
      $this->resendTime = null;
    } else {
      $this->resendTime = "You can request OTP after {$minutes} minutes, {$seconds} seconds.";
    }

    $this->dispatch('openResendModal');
  }
  public function closeResendModal()
  {
    $this->dispatch(event: 'closeResendModal');
  }
  public function resendCode()
  {
    $otpCode = mt_rand(100000, 999999);
    $this->otpUser->update([
      'otp' => $otpCode,
      'expires_at' => now()->addMinutes(value: 5),
    ]);
    
    Mail::to(Auth::user()->email)->send(new OtpMail(Auth::user(), $otpCode));

    // SMS Notification
    $messageData = [
      'app_key' => config('app.api.app_key'),
      'app_secret' => config('app.api.app_secret'),
      'msisdn' => Auth::user()->number,
      'content' => 'Your One-Time Password (OTP) for Secure Access to e-Procurement: ' . $otpCode,
      'shortcode_mask' => 'TEI',
    ];
    $response = Http::withHeaders([
      'Accept' => 'application/json',
    ])->withoutVerifying()
      ->post('https://api.m360.com.ph/v3/api/broadcast', $messageData);

    return redirect()->route('otp-page');
  }

  public function loginPage()
  {
    auth()->logout();

    return redirect()->route('login');
  }
  public function render()
  {
    return view('livewire.otp-page');
  }
}
