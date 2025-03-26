<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Request;
use App\Helpers\LogsActivity;
use App\Helpers\SmsNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Http;

new #[Layout('layouts.guest')] class extends Component 
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();
        // dd(Auth::user()->name);
        if (Auth::user()->name == 'superadmin') {
            // $this->redirect(route('dashboard', absolute: false), navigate: true);
            $this->redirect(route('dashboard'));
        } else {
            $userOtp = UserOtp::where('user_id', Auth::user()->id)->first();
            $otpCode = $userOtp ? $userOtp->otp : mt_rand(100000, 999999);

            if (!$userOtp) {
                UserOtp::create([
                    'user_id' => Auth::user()->id,
                    'otp' => $otpCode,
                    'expires_at' => now()->addMinutes(5),
                ]);
            }

            // Mail Notification
            Mail::to(Auth::user()->email)->send(new OtpMail(Auth::user(), $otpCode));

            // SMS Notification
            $content = 'Your One-Time Password (OTP) for Secure Access to e-Procurement: ' . $otpCode;
            SmsNotification::globeSms(Auth::user()->number, $content);

            $this->redirect(route('otp-page', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />

  <form wire:submit="login">
    <!-- Email Address -->
    <div>
      <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required
        autofocus autocomplete="username" placeholder="Email" />
      <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">

      <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password" name="password"
        required autocomplete="current-password" placeholder="Password" />

      <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="mt-4 flex justify-between">
      <label for="remember" class="inline-flex items-center">
        <input wire:model="form.remember" id="remember" type="checkbox"
          class="rounded border-gray-300 tei-text-secondary shadow-sm focus:ring-orange-700" name="remember">
        <span class="ms-2 text-sm text-white">{{ __('Remember me') }}</span>
      </label>
      @if (Route::has('password.request'))
        <a class="underline text-sm text-white hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          href="{{ route('password.request') }}" wire:navigate>
          {{ __('Forgot your password?') }}
        </a>
      @endif
    </div>

    <div class="flex items-center justify-end mt-4">
      <button type="submit" wire:loading.remove wire:target="login"
        class="w-full tei-btn-secondary focus:ring-4 focus:ring-orange-700 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2">{{ __('Sign In') }}</button>
      {{-- <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button> --}}
      <div class="w-full rounded-full tei-bg-light flex justify-center p-4 md:p-5" wire:loading wire:target="login">
        <div class="flex justify-center ">
          <div class="loading-small loading-main">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center text-white text-xs underline mt-2">
      <a class="hover:text-orange-600" href="https://tarlacelectric.com/privacy-policy" target="_blank" rel="noopener noreferrer">TEI Privacy Policy Statement</a>
    </div>
  </form>
</div>
