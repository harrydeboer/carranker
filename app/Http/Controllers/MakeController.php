<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\MakeRepository;
use Illuminate\Http\Response;

class MakeController extends BaseController
{
    protected $title;

    public function view(string $makename): Response
    {
        $makename = rawurldecode($makename);
        $cacheString = 'makepage' . $makename;
        $this->title = $makename;

        if ($this->redis->get($cacheString) === false) {
            $makeRepository = new MakeRepository();
            $make = $makeRepository->getByName($makename);

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