<?php

use App\Http\Middleware\CheckAccessRights;
use App\Http\Middleware\CheckOriginalRoute;
use App\Http\Middleware\LogUserLogin;
use App\Http\Middleware\OtpAccess;
use App\Http\Middleware\SelectedEnvelope;
use App\Http\Middleware\VendorEnvelopeAccess;
use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckAdminRole;
use App\Http\Middleware\CheckVendorRole;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'client' => CheckClientCredentials::class,
      'admin.role' => CheckAdminRole::class,
      'vendor.role' => CheckVendorRole::class,
      'envelope' => SelectedEnvelope::class,
      'vendorEnvelope' => VendorEnvelopeAccess::class,
      'access' => CheckAccessRights::class,
      'log.user.login' => LogUserLogin::class,
      'otp.access' => OtpAccess::class,
      'initalRoute' => CheckOriginalRoute::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
