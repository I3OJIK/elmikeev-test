<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:sync-all-data')
    ->twiceDaily(6, 18)
    ->description('Синхронизация всех данных');
