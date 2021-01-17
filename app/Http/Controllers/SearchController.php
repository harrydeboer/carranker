<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use App\Repositories\Elastic\TrimRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function __construct(
        private MakeRepository $makeRepository,
        private ModelRepository $modelRepository,
        private TrimRepository $trimRepository,
    ) {
    }

    public function view(Request $request): Response
    {
        $formData = $request->validate($this->rules());

        $viewData = [
            'title' => 'Search results',
            'makes' => $this->makeRepository->findForSearch($formData['query']),
            'models' => $this->modelRepository->findForSearch($formData['query']),
            'trims' => $this->trimRepository->findForSearch($formData['query']),
        ];

        return response()->view('search.index', $viewData);
    }

    public function rules(): array
    {
        return [
            'query' => 'string|required',
        ];
    }
}
