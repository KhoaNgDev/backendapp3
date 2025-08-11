<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('maintenance:remind')
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer();