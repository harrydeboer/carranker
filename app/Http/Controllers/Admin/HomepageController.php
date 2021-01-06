<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function view()
    {
        return response()->view('admin.index', []);
    }
}
