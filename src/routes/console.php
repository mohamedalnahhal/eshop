<?php

use App\Console\Commands\ExpireSubscriptions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ExpireSubscriptions::class)->hourly();
