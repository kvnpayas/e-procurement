<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;

class CommandRestrictionServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    // dd(app()->environment('local'));
    Event::listen(CommandStarting::class, function (CommandStarting $event) {
      $restrictedCommands = [
        'migrate:refresh',
        'migrate:fresh',
        'migrate:reset',
        'db:wipe',
      ];

      if (in_array($event->command, $restrictedCommands)) {
        $event->output->writeln('<error>This command is restricted.</error>');
        exit(1);
      }
    });
  }
}
