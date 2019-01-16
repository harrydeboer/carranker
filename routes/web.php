<?php

declare(strict_types=1);

Route::get('', ['as' => 'Home', 'uses' => 'HomepageController@view']);
Route::post('getModelNames', ['as' => 'base.getModelNames', 'uses' => 'Controller@getModelNames']);
Route::get('search', ['as' => 'base.search', 'uses' => 'Controller@search']);
Route::get('sitemap', 'SitemapController@makeSitemap');
Route::post('filterTop', ['as' => 'filterTop', 'uses' => 'HomepageController@filterTop']);
Route::post('showMoreTopTable/{numberOfRows}/{offset}', ['as' => 'showMoreTopTable', 'uses' => 'HomepageController@showMoreTopTable']);
Route::get('contact', ['as' => 'Contact', 'uses' => 'ContactController@view']);
Route::post('sendMail', ['as' => 'contact.sendMail', 'uses' => 'ContactController@sendMail']);
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@register']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::get('make/{make}', 'MakeController@view');
Route::get('model/{make}/{model}/{trimId}', 'ModelpageController@view');
Route::get('model/{make}/{model}', ['as' => 'make.model', 'uses' => 'ModelpageController@view']);
Route::post('model/{make}/{model}', 'ModelpageController@view');

/** Catch all remaining routes for the cms pages. */
Route::get('{url}', 'CmsController@view');

