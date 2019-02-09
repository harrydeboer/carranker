<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class MakeController extends BaseController
{
    protected $title;

    public function view(string $makename): Response
    {
        /**
         * The make that the user visits is stored in the session
         * and is used to fill the make and model selects of the navigation.
         */
        $cacheString = 'makepage';
        $makename = rawurldecode($makename);
        $this->title = $makename;

        if ($this->redis->get($cacheString) === false) {
            $make = $this->makeRepository->getByName($makename);

            $models = $make->getModels();
            $data = [
                'make' => $make,
                'models' => $models,
            ];

            $response = response()->view('make.index', $data, 200);

            $this->redis->set($cacheString, $response->getContent(), $this->cacheExpire);

            return $response;
        }

        return response($this->redis->get($cacheString), 200);
    }
}