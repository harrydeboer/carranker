<?php

declare(strict_types=1);

Route::get('', ['as' => 'Home', 'uses' => 'HomepageController@view']);
Route::get('search', ['as' => 'base.search', 'uses' => 'BaseController@search']);
Route::get('filterTop', ['as' => 'filterTop', 'uses' => 'HomepageController@filterTop']);
Route::get('showMoreTopTable/{numberOfRows}/{offset}', ['as' => 'showMoreTopTable', 'uses' => 'HomepageController@showMoreTopTable']);
Route::get('contact', 'ContactController@view');
Route::post('contact', ['as' => 'contact.view', 'uses' => 'ContactController@view']);
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@register']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::get('make/{make}', 'MakeController@view');
Route::get('model/{make}/{model}', 'ModelpageController@view')->where('model', '.*');
Route::post('model/{make}/{model}', ['as' => 'make.model', 'uses' => 'ModelpageController@view'])->where('model', '.*');

/** Catch all remaining routes for the cms pages. */
Route::get('{url?}', 'CmsController@view')->where('url', '.*');


