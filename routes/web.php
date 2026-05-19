<?php

use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::resource('frames/{token}/subscribes', SubscribeController::class)
        ->only(['create', 'store']);
