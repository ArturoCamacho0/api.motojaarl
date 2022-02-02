<?php

use Illuminate\Support\Facades\Route;

// Version 1
route::group(['prefix' => 'v1'], function () {
	// Login route
	route::post('login', [\App\Http\Controllers\V1\UserController::class, 'login']);

	// Routes that need authentication
	route::group(['middleware' => 'auth:api'], function () {
		// User route without role admin
		route::get('users/{id}', [\App\Http\Controllers\V1\UserController::class, 'show']);

		// Direction routes without role admin
		route::get('directions', [\App\Http\Controllers\V1\DirectionController::class, 'index']);
		route::get('directions/{id}', [\App\Http\Controllers\V1\DirectionController::class, 'show']);

		// Business route without role admin
		route::get('business/{id}', [\App\Http\Controllers\V1\BusinessController::class, 'show']);

		// Price routes without role admin
		route::get('prices', [\App\Http\Controllers\V1\PriceController::class, 'index']);
		route::get('prices/{id}', [\App\Http\Controllers\V1\PriceController::class, 'show']);

		// Category routes without role admin
		route::get('categories', [\App\Http\Controllers\V1\CategoryController::class, 'index']);
		route::get('categories/{id}', [\App\Http\Controllers\V1\CategoryController::class, 'show']);

		// Customer type routes without admin
		route::get('customer_types', [\App\Http\Controllers\V1\CustomerTypeController::class, 'index']);
		route::get('customer_types/{id}', [\App\Http\Controllers\V1\CustomerTypeController::class, 'show']);

		// Phone routes without admin
		route::get('phones', [\App\Http\Controllers\V1\PhoneController::class, 'index']);
		route::get('phones/{id}', [\App\Http\Controllers\V1\PhoneController::class, 'show']);

		// Sale routes without admin
		route::get('sales/user/{id}', [\App\Http\Controllers\V1\SaleController::class, 'getByUserId']);

		// Outgoing routes without admin
		route::get('outgoings/user/{id}', [\App\Http\Controllers\V1\OutgoingController::class, 'getByUser']);

		// Routes that needs a role admin
		Route::group(['middleware' => 'role:admin'], function () {
			// User routes
			route::resource('users', \App\Http\Controllers\V1\UserController::class)
				->except('login', 'show');

			// Direction routes
			route::resource('directions', \App\Http\Controllers\V1\DirectionController::class)
				->except('show', 'index');

			// Business routes
			route::resource('business', \App\Http\Controllers\V1\BusinessController::class)
				->except('show');

			// Price routes
			route::resource('prices', \App\Http\Controllers\V1\PriceController::class)
				->except('show', 'index');

			// Category routes
			route::resource('categories', \App\Http\Controllers\V1\CategoryController::class)
				->except('show', 'index');

			// Customer type routes
			route::resource('customer_types', \App\Http\Controllers\V1\CustomerTypeController::class)
				->except('index', 'show');

			// Phone routes
			route::resource('phones', \App\Http\Controllers\V1\PhoneController::class)
				->except('index', 'show');

			// Sale routes
			route::resource('sales', \App\Http\Controllers\V1\SaleController::class);
			route::post('sales/getByDate', [\App\Http\Controllers\V1\SaleController::class, 'getByDate']);
			route::post('sales/total', [\App\Http\Controllers\V1\SaleController::class, 'getTotalSales']);
			route::post('sales/total/by_date', [\App\Http\Controllers\V1\SaleController::class, 'getTotalByDate']);

			// Outgoing routes
			route::resource('outgoings', \App\Http\Controllers\V1\OutgoingController::class);
			route::post('outgoings/by_date', [\App\Http\Controllers\V1\OutgoingController::class, 'getByDate']);
			route::post('outgoings/total', [\App\Http\Controllers\V1\OutgoingController::class, 'getTotal']);
			route::post('outgoings/total/by_date', [\App\Http\Controllers\V1\OutgoingController::class, 'getTotalByDate']);
		});
	});
});
