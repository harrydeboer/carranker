<?php

declare(strict_types=1);

Route::get('', ['as' => 'Home', 'uses' => 'HomepageController@view']);
Route::get('search', ['as' => 'base.search', 'uses' => 'Controller@search']);
Route::get('sitemap', 'SitemapController@makeSitemap');
Route::get('filterTop', ['as' => 'filterTop', 'uses' => 'HomepageController@filterTop']);
Route::get('showMoreTopTable/{numberOfRows}/{offset}', ['as' => 'showMoreTopTable', 'uses' => 'HomepageController@showMoreTopTable']);
Route::get('contact', ['as' => 'Contact', 'uses' => 'ContactController@view']);
Route::post('sendMail', ['as' => 'contact.sendMail', 'uses' => 'ContactController@sendMail']);
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@register']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::get('make/{make}', 'MakeController@view');
Route::get('model/{make}/{model}/{trimId?}', 'ModelpageController@view');
Route::post('model/{make}/{model}/{trimId?}', ['as' => 'make.model', 'uses' => 'ModelpageController@view']);

/** Catch all remaining routes for the cms pages. */
Route::get('{url?}', 'CmsController@view')->where('url', '.*');


