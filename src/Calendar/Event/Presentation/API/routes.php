<?php

use Illuminate\Support\Facades\Route;
use Src\Calendar\Event\Presentation\API\EventController;

Route::group(['prefix' => 'events'], function () {
    Route::get('', [EventController::class, 'index']);
    Route::post('new', [EventController::class, 'store']);
    Route::put('{id}', [EventController::class, 'update']);
    Route::delete('{id}', [EventController::class, 'delete']);
});
