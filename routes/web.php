<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::post('contact', ['as' => 'contact.view', 'uses' => 'ContactController@view']);
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::post('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@register']);
Route::post('ratecar', ['as' => 'ratecar', 'uses' => 'ModelpageController@ratecar']);
Route::get('auth', ['as' => 'auth', 'uses' => 'Auth\LoginController@view']);
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::get('model/{make}/{model}', 'ModelpageController@view')->where('model', '.*');
Route::get('contact', 'ContactController@view');
Route::get('filterTop', ['as' => 'filterTop', 'uses' => 'HomepageController@filterTop']);
Route::get('showMoreTopTable/{numberOfRows}/{offset}', ['as' => 'showMoreTopTable', 'uses' => 'HomepageController@showMoreTopTable']);
Route::get('/forgot-password', ['uses' => 'Auth\ForgotPasswordController@view'])
    ->middleware('guest')->name('password.request');
Route::post('/forgot-password', ['uses' => 'Auth\ForgotPasswordController@email'])
    ->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', ['uses' => 'Auth\ResetPasswordController@view'])
    ->middleware('guest')->name('password.reset');
Route::post('/reset-password', ['uses' => 'Auth\ResetPasswordController@update'])
    ->middleware('guest')->name('password.update');

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
