<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CMSPageController;
use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\MakePageController;
use App\Http\Controllers\ModelPageController;
use App\Http\Controllers\Admin\ModelPageController as ModelPageControllerAdmin;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('filterTop', [HomePageController::class, 'filterTop'])->name('filterTop');
Route::get('showMoreTopTable', [HomePageController::class, 'showMoreTopTable'])->name('showMoreTopTable');
Route::get('model/{make}/{model}', [ModelPageController::class, 'view'])
    ->name('modelPage')->where('model', '.*');
Route::post('rateCar', [ModelPageControllerAdmin::class, 'rateCar'])
    ->name('rateCar')->middleware('verified');
Route::get('contact', [ContactPageController::class, 'view'])->name('contactPage');
Route::post('contact', [ContactPageController::class, 'sendMail'])->name('contact.sendMail');

Route::get('email/verify/with/mail', [VerificationController::class, 'showAndMail'])
    ->name('verification.notice.with.mail')->middleware('auth');
Auth::routes(['verify' => true]);

Route::get('home', function ()
{
	return redirect('/');
});

Route::group(['middleware' => 'cacheable'], function ()
{
	Route::get('search', [SearchController::class, 'view'])->name('search.view');
	Route::get('', [HomePageController::class, 'view'])->name('Home');
	Route::get('make/{make}', [MakePageController::class, 'view'])->name('makePage');

	/** Catch all remaining routes for the cms pages. */
	Route::get('{url?}', [CMSPageController::class, 'view'])
        ->name('cMSPage')->where('url', '.*');
});
