<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MakeRepositoryInterface;
use App\Repositories\Interfaces\ModelRepositoryInterface;
use App\Repositories\Interfaces\TrimRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function __construct(
        private MakeRepositoryInterface $makeRepository,
        private ModelRepositoryInterface $modelRepository,
        private TrimRepositoryInterface $trimRepository,
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
