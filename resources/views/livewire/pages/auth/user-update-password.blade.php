<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Helpers\LogsActivity;

new #[Layout('layouts.guest')] class extends Component 
{
    public $user;
    public $password, $password_confirmation;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function createPassword()
    {
        try {
            $this->validate([
                'password' => 'required|confirmed|min:6',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $fieldName = array_keys($errors);
            $errorMessage = 'Validation Error on fields(' . implode(', ', $fieldName) . ')';
            LogsActivity::passwordReset($this->user->email, $errorMessage, 'Update Password');
        }
        $this->validate([
            'password' => 'required|confirmed|min:6',
        ]);
        $this->user->update([
            'password' => Hash::make($this->password),
            'token' => null,
        ]);
        LogsActivity::passwordReset($this->user->email, 'Success!', 'Update Password');
        Session::flash('status', 'Your password has been successfully update. Please log in with your new password.');
        $this->redirect(route('login'));
    }
}; ?>

<div>
  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />
  <label class="tei-text-light font-extrabold">Welcome!</label>
  <span class="tei-text-light font-extrabold">{{ $user->name }}</span>
  <p class="text-black uppercase text-xs font-extrabold bg-green-500 my-5 p-2 shadow-md rounded-sm">Please create a new
    password to secure your account.</p>
  <form wire:submit="createPassword">
    <!-- Password -->
    <div class="mt-4">

      <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required
        autocomplete="current-password" placeholder="Password" />

      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">

      <x-text-input wire:model="password_confirmation" id="password" class="block mt-1 w-full" type="password"
        name="password" required autocomplete="current-password" placeholder="Confirm Password" />

      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
      <button type="submit"
        class="w-full tei-btn-secondary focus:ring-4 focus:ring-orange-700 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2">{{ __('Create Password') }}</button>
      {{-- <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button> --}}
    </div>
  </form>
</div>
