<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class MakeController extends Controller
{
    public function view(string $makename, Request $request)
    {
        $make = $this->makeRepository->getByName(urldecode($makename));
        $session = $request->session();
        $session->put('makename', $make->getName());
        $session->put('modelname', null);
        $this->shareSessionCars($session);

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