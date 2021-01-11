<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\MailUserController;
use App\Http\Controllers\Admin\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('', [HomePageController::class, 'view'])->name('admin.dashboard');
Route::get('mailUsers', [MailUserController::class, 'view'])->name('admin.mail.users');
Route::post('mailUsers/create', [MailUserController::class, 'create'])->name('admin.mail.users.create');
Route::post('mailUsers/update', [MailUserController::class, 'update'])->name('admin.mail.users.update');
Route::post('mailUsers/update/password', [MailUserController::class, 'updatePassword'])
    ->name('admin.mail.users.update.password');
Route::post('mailUsers/delete', [MailUserController::class, 'delete'])->name('admin.mail.users.delete');
Route::get('reviews', [ReviewController::class, 'view'])->name('admin.reviews');
Route::post('reviews/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
Route::post('reviews/delete', [ReviewController::class, 'delete'])->name('admin.reviews.delete');
