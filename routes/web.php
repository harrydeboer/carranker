<?php

declare(strict_types=1);

Route::get('search', ['as' => 'base.search', 'uses' => 'BaseController@search']);
Route::get('filterTop', ['as' => 'filterTop', 'uses' => 'HomepageController@filterTop']);
Route::get('showMoreTopTable/{numberOfRows}/{offset}', ['as' => 'showMoreTopTable', 'uses' => 'HomepageController@showMoreTopTable']);
Route::get('contact', 'ContactController@view');
Route::post('contact', ['as' => 'contact.view', 'uses' => 'ContactController@view']);
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::post('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@register']);
Route::get('model/{make}/{model}', 'ModelpageController@view')->where('model', '.*');
Route::post('model/{make}/{model}', ['as' => 'make.model', 'uses' => 'ModelpageController@view'])->where('model', '.*');

Route::group(['middleware' => 'cacheable'], function() {
    Route::get('', ['as' => 'Home', 'uses' => 'HomepageController@view']);
    Route::get('make/{make}', 'MakeController@view');

    /** Catch all remaining routes for the cms pages. */
    Route::get('{url?}', 'CmsController@view')->where('url', '.*');
});

