<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SmsNotification
{

  public static function globeSms($userNumber, string $content)
  {
    // SMS Notification
    $messageData = [
      'app_key' => config('app.api.app_key'),
      'app_secret' => config('app.api.app_secret'),
      'msisdn' => $userNumber,
      'content' => $content,
      'shortcode_mask' => 'TEI',
    ];
    $response = Http::withHeaders([
      'Accept' => 'application/json',
    ])->withoutVerifying()
      ->post('https://api.m360.com.ph/v3/api/broadcast', $messageData);
  }
}

