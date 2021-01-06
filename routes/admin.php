<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('', ['as' => 'admin.dashboard', 'uses' => 'HomepageController@view']);
