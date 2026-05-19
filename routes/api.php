<?php

use App\Http\ApiControllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function(){
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/avalible-time', [ServiceController::class, 'shedulesFromWorker'])->name('avalibleTime.index');
    Route::get('/available-weekdays', [ServiceController::class, 'availableWeekdays'])->name('availableWeekdays');
});
