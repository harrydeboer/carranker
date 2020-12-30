<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('contact', ['as' => 'contact.view', 'uses' => 'ContactController@view']);
Route::post('ratecar', ['as' => 'ratecar', 'uses' => 'ModelpageController@ratecar'])->middleware('verified');
Route::get('model/{make}/{model}', 'ModelpageController@view')->where('model', '.*');
Route::get('contact', 'ContactController@view');
Route::get('filterTop', ['as' => 'filterTop', 'uses' => 'HomepageController@filterTop']);
Route::get('showMoreTopTable/{numberOfRows}/{offset}', ['as' => 'showMoreTopTable',
    'uses' => 'HomepageController@showMoreTopTable']);

Route::get('/email/verify/with/mail', ['as' => 'verification.notice.with.mail',
    'uses' => 'Auth\VerificationController@showAndMail'])->middleware('auth');
Auth::routes(['verify' => true]);

Route::get('home', function ()
{
	return redirect('/');
});

Route::group(['middleware' => 'cacheable'], function ()
{
	Route::get('search', ['as' => 'search.view', 'uses' => 'SearchController@view']);
	Route::get('', ['as' => 'Home', 'uses' => 'HomepageController@view']);
	Route::get('make/{make}', 'MakeController@view');

	/** Catch all remaining routes for the cms pages. */
	Route::get('{url?}', 'CmsController@view')->where('url', '.*');
});
