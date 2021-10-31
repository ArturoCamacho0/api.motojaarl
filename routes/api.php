<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Version 1
route::group(['prefix' => 'v1'], function() {
    route::post('login', [\App\Http\Controllers\V1\UserController::class, 'login']);

    route::group(['middleware' => 'auth:api'], function() {
        Route::get('user/{id}', [\App\Http\Controllers\V1\UserController::class, 'show']);

        Route::group(['middleware' => 'role:admin'], function() {
            route::resource('user', \App\Http\Controllers\V1\UserController::class)
                ->except('login', 'show');
        });
    });
});
