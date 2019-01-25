<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class MakeController extends Controller
{
    public function view(string $makename): \Illuminate\View\View
    {
        $make = $this->makeRepository->getByName(rawurldecode($makename));

        /**
         * The make that the user visits is stored in the session
         * and is used to fill the make and model selects of the navigation.
         */
        $session = session();
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