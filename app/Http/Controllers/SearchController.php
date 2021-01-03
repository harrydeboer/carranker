<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Forms\SearchForm;
use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function __construct(private MakeRepository $makeRepository,
                                private ModelRepository $modelRepository,
                                private TrimRepository $trimRepository){}

    public function view(Request $request): Response
    {
        $searchForm = new SearchForm($request->all());

        if ($searchForm->validateFull($request)) {
            $data = [
                'title' => 'Search results',
                'makes' => $this->makeRepository->findForSearch($searchForm->query),
                'models' => $this->modelRepository->findForSearch($searchForm->query),
                'trims' => $this->trimRepository->findForSearch($searchForm->query),
            ];

            return response()->view('search.index', $data, 200);
        }

        $data = [
            'title' => 'Search results',
            'makes' => [],
            'models' => [],
            'trims' => [],
        ];

        return response()->view('search.index', $data, 200);
    }
}
