<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MakeController extends Controller
{
    public function view(string $makename): Response
    {
        $user = Auth::user();
        $cacheString = is_null($user) ? 'makepage' . $makename : 'makepageauth' . $makename;

        if ($this->redis->get($cacheString) === false) {
            $this->decorator();
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

            $response = response()->view('make.index', $data, 200);

            $this->redis->set($cacheString, $response->getContent(), self::cacheExpire);

            return $response;
        }

        return response($this->redis->get($cacheString), 200);
    }
}