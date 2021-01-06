<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class HomePageController extends Controller
{
    public function view(): Response
    {
        $data = [
            'title' => 'Home',
            'controller' => 'admin',
        ];
        return response()->view('admin.homePage.index', $data);
    }
}
