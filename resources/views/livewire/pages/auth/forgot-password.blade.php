<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use App\Mail\VendorForgotPassword;
use App\Helpers\LogsActivity;

new #[Layout('layouts.guest')] class extends Component 
{
    public string $email = '';
    public string $status = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);
        $user = User::where('email', $this->email)->first();
        if ($user->role_id != 2) {
            $this->addError('email', 'Please Contact Support!');

            return;
        }
        $token = Str::random(40);
        $user->update([
            'token' => $token,
        ]);

        try {
            Mail::to($user->email)->send(new VendorForgotPassword($user, $token));
            $this->status = 'An email has been sent to you with a link to reset your password.';
            LogsActivity::emailNotification($user->email, 'Email sent successfully!', 'Password Reset Email');
        } catch (\Exception $e) {
            LogsActivity::emailNotification($user->email, $e->getMessage(), 'Password Reset');
            $this->status = '';
        }
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $this->only('email')
        // );

        // if ($status != Password::RESET_LINK_SENT) {
        //     $this->addError('email', __($status));

        //     return;
        // }

        // $this->reset('email');

        session()->flash('status', $this->status);
    }
}; ?>

<div>
  <div class="mb-4 text-sm text-white">
    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
  </div>

  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />
  @if (!$status)
    <form wire:submit="sendPasswordResetLink" wire:loading.remove wire:target="sendPasswordResetLink">
      <!-- Email Address -->
      <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
          autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      <div class="flex items-center justify-end mt-4">
        <button type="submit"
          class="w-full tei-btn-secondary focus:ring-4 focus:ring-orange-700 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2">{{ __('Email Password Reset Link') }}</button>
      </div>
    </form>
  @endif
  <div class="w-full rounded-md" wire:loading wire:target="sendPasswordResetLink">
    <div class="text-center">
      <span class="text-white font-extrabold">Please wait</span>
    </div>
    <div class="flex justify-center">
      <div class="loading loading-main">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
</div>
