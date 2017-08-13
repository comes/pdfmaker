<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/meta', MetaController::class,['only' => [
    'index', 'show', 'store', 'update','destroy'
]]);

Route::resource('/product', ProductController::class,['only' => [
    'index', 'show', 'store', 'update','destroy'
]]);

Route::get('/product/meta/describe', 'ProductController@describe');

Route::resource('/catalog', CatalogController::class,['only' => [
    'index', 'show', 'store', 'update','destroy'
]]);

Route::get('/catalog/meta/describe', 'CatalogController@describe');
