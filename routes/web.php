<?php

use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\Subscribe;
use App\resources\views\mails\SubscribesInfo;

Route::resource('/frames/{token}/subscribes', SubscribeController::class)
    ->only(['create', 'store']);

Route::get('/frames/{token}/subscribes/test', [SubscribeController::class, 'test']);
