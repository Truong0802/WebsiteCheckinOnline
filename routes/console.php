<?php

use Illuminate\Support\Facades\Schedule;

// Dọn Sanctum token hết hạn quá 24h (cần chạy scheduler: php artisan schedule:work).
Schedule::command('sanctum:prune-expired --hours=24')->daily();
