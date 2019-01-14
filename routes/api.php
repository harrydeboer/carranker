<?php

declare(strict_types=1);

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

Route::get('/make/{makeId}', 'APIController@viewMake')->middleware('auth:api');
Route::get('/model/{modelId}', 'APIController@viewModel')->middleware('auth:api');
Route::get('/trim/{trimId}', 'APIController@viewTrim')->middleware('auth:api');
Route::get('getModelNames/{makename}', 'ModelnamesController@getModelNames');