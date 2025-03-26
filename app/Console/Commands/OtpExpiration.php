<?php

namespace App\Console\Commands;

use App\Models\UserOtp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OtpExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:otp-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete record and destroy session of user when otp expirations met.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOtp = UserOtp::where('expires_at', '<', now())->get();
        $count = 0;
        foreach($expiredOtp as $user){
          DB::table('sessions')->where('user_id', $user->user_id)->delete();
          $user->delete();
          $count++;
        }

        $this->info($count . ' otp expired');
    }
}
