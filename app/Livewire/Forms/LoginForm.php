<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\LogsActivity;
use Livewire\Attributes\Validate;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginForm extends Form
{
  #[Validate('required|string|email')]
  public string $email = '';

  #[Validate('required|string')]
  public string $password = '';

  #[Validate('boolean')]
  public bool $remember = false;

  /**
   * Attempt to authenticate the request's credentials.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function authenticate(): void
  {
    $this->ensureIsNotRateLimited();

    $user = User::where('email', $this->email)->first();

    // Check if the user exists and if the password is null
    if ($user && is_null($user->password)) {
      LogsActivity::loggedInFailed($this->email, 'Login Failed: Password not set up');
      throw ValidationException::withMessages([
        'form.email' => 'This account has not been set up with a password. Please contact support.',
      ]);
    }

    // Check if the user is inactive
    if ($user && !$user->active) {
      LogsActivity::loggedInFailed($this->email, 'Login Failed: Account Inactive');
      throw ValidationException::withMessages([
        'form.email' => 'This account has been inactive. Please contact support.',
      ]);
    }
    
    if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
      RateLimiter::hit($this->throttleKey());
      LogsActivity::loggedInFailed($this->email, 'Login Failed: Wrong Credentials');
      throw ValidationException::withMessages([
        'form.email' => trans('auth.failed'),
      ]);
    }
    RateLimiter::clear($this->throttleKey());
  }

  public function logFailedAttempt(string $message)
  {
    $ipAddress = Request::ip();
    Log::info('User Activity Logs - '.$message, [
      'email' => $this->email,
      'ip_address' => $ipAddress,
      'logged_in_attempt_at' => now(),
    ]);
  }


  /**
   * Ensure the authentication request is not rate limited.
   */
  protected function ensureIsNotRateLimited(): void
  {
    if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
      return;
    }

    event(new Lockout(request()));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
      'form.email' => trans('auth.throttle', [
        'seconds' => $seconds,
        'minutes' => ceil($seconds / 60),
      ]),
    ]);
  }

  /**
   * Get the authentication rate limiting throttle key.
   */
  protected function throttleKey(): string
  {
    return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
  }
}
