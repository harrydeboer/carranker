<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class MakeController extends Controller
{
    public function view($makename)
    {
        $makename = str_replace('_', ' ', $makename);
        $make = $this->makeRepository->getByName($makename);
        $models = $make->getModels();
        $data = [
            'controller' => 'make',
            'title' => $make->getName(),
            'make' => $make,
            'models' => $models,
        ];

        return View::make('make.index')->with($data);
    }
}