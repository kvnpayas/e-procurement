<?php

use App\Console\Commands\OtpExpiration;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateBidAndVendorStatus;


Schedule::command(UpdateBidAndVendorStatus::class)->everyMinute();
Schedule::command(OtpExpiration::class)->everyFiveSeconds();
