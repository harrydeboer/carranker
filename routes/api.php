<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

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

/** No authentication for getting model names of a make, because this route has to be very fast. */
Route::get( 'getModelNames/{makename}', 'APIController@getModelNames' );

Route::group(['middleware' => 'cacheable'], function ()
{
	Route::get( 'sitemap', 'APIController@makeSitemap' );
});
