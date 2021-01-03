<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('filterTop', ['as' => 'filterTop', 'uses' => 'HomePageController@filterTop']);
Route::get('showMoreTopTable', ['as' => 'showMoreTopTable', 'uses' => 'HomePageController@showMoreTopTable']);
Route::get('model/{make}/{model}', ['as' => 'modelPage','uses' => 'ModelPageController@view'])
    ->where('model', '.*');
Route::post('rateCar', ['as' => 'rateCar', 'uses' => 'ModelPageController@rateCar'])
    ->middleware('verified');
Route::get('contact', ['as' => 'contactPage', 'uses' => 'ContactPageController@view']);
Route::post('contact', ['as' => 'contact.view', 'uses' => 'ContactPageController@view']);

Route::get('email/verify/with/mail', ['as' => 'verification.notice.with.mail',
    'uses' => 'Auth\VerificationController@showAndMail'])->middleware('auth');
Auth::routes(['verify' => true]);

Route::get('home', function ()
{
	return redirect('/');
});

Route::group(['middleware' => 'cacheable'], function ()
{
	Route::get('search', ['as' => 'search.view', 'uses' => 'SearchController@view']);
	Route::get('', ['as' => 'Home', 'uses' => 'HomePageController@view']);
	Route::get('make/{make}', ['as' => 'makePage', 'uses' => 'MakePageController@view']);

	/** Catch all remaining routes for the cms pages. */
	Route::get('{url?}', ['as' => 'cmsPage', 'uses' => 'CmsPageController@view'])
        ->where('url', '.*');
});
