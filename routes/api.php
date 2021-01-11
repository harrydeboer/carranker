<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Controller;
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

Route::get('/make/{makeId}', [Controller::class, 'viewMake'])->middleware('auth:api');
Route::get('/model/{modelId}', [Controller::class, 'viewModel'])->middleware('auth:api');
Route::get('/trim/{trimId}', [Controller::class, 'viewTrim'])->middleware('auth:api');

/** No authentication for getting model names of a make, because this route has to be very fast. */
Route::get( 'getModelNames/{makeName}', [Controller::class, 'getModelNames']);

Route::group(['middleware' => 'cacheable'], function ()
{
	Route::get( 'sitemap', [Controller::class, 'makeSitemap'] );
});
