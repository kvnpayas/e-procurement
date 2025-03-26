<?php
namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogsActivity
{

  protected static $ipAddress;

  public static function initialize()
  {
    self::$ipAddress = request()->ip();
  }
  public static function loggedIn()
  {
    $user = Auth::user();

    Log::channel('info_log')->info('User Activity Logs - User Logged In', [
      'user_id' => $user->id,
      'role_id' => $user->role_id,
      'email' => $user->email,
      'ip_address' => self::$ipAddress,
      'logged_in_at' => now(),
    ]);

    return true;
  }

  public static function loggedOut()
  {
    $user = Auth::user();
    Log::channel('info_log')->info('User Activity Logs - User Logged Out', [
      'user_id' => $user->id,
      'role_id' => $user->role_id,
      'email' => $user->email,
      'ip_address' =>self::$ipAddress,
      'logged_out_at' => now(),
    ]);

    return true;
  }

  public static function loggedInFailed(string $email, string $message)
  {
    Log::channel('info_log')->info('User Activity Logs - ' . $message, [
      'email' => $email,
      'ip_address' => self::$ipAddress,
      'logged_in_attempt_at' => now(),
    ]);

    return true;
  }

  public static function passwordReset(string $email, string $message, string $module)
  {
    $user = User::where('email', $email)->first();

    Log::channel('info_log')->info('User Activity Logs - '.$module.': '.$message, [
      'user_id' => $user->id,
      'role_id' => $user->role_id,
      'email' => $user->email,
      'ip_address' => self::$ipAddress,
      'password_reset_at' => now(),
    ]);

    return true;
  }

  public static function emailNotification(string $email, string $message, string $module)
  {
    Log::channel('info_log')->info('Email Notifications - ' .$module.': ' . $message, [
      'email' => $email,
      'ip_address' => self::$ipAddress,
      'sent_at' => now(),
    ]);

    return true;
  }

  public static function userMaintenance(string $email, string $message, string $module)
  {
    $user = Auth::user();

    Log::channel('info_log')->info('User Activity Logs - '.$module.': '.$message, [
      'user_email' => $email,
      'ip_address' => self::$ipAddress,
      'created_by' => $user->id,
      'created_at' => now(),
    ]);

    return true;
  }


}
LogsActivity::initialize();

