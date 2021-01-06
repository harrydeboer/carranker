<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('', ['as' => 'admin.dashboard', 'uses' => 'HomePageController@view']);
Route::get('mailUsers', ['as' => 'admin.mail.users', 'uses' => 'MailUserController@view']);
Route::post('mailUsers/create', ['as' => 'admin.mail.users.create', 'uses' => 'MailUserController@create']);
Route::post('mailUsers/update', ['as' => 'admin.mail.users.update', 'uses' => 'MailUserController@update']);
Route::post('mailUsers/update/password', ['as' => 'admin.mail.users.update.password',
    'uses' => 'MailUserController@updatePassword']);
Route::post('mailUsers/delete', ['as' => 'admin.mail.users.delete', 'uses' => 'MailUserController@delete']);
Route::get('reviews', ['as' => 'admin.reviews', 'uses' => 'ReviewController@view']);
Route::post('reviews/approve', ['as' => 'admin.reviews.approve', 'uses' => 'ReviewController@approve']);
Route::post('reviews/delete', ['as' => 'admin.reviews.delete', 'uses' => 'ReviewController@delete']);
