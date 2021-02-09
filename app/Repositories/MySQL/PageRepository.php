<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PageRepository implements PageRepositoryInterface
{
    public function __construct(
        private Page $page,
    ) {
    }

    public function all(): Collection
    {
        return Page::all();
    }

    public function get(int $id): Page
    {
        return $this->page->findOrFail($id);
    }

    public function create(array $createArray): Page
    {
        $model = new Page($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        Page::destroy($id);
    }

    public function findByName(string $name): ?Page
    {
        return $this->page->where('name', $name)->first();
    }

    public function getByName(string $name): Page
    {
        $page = $this->page->where('name', $name)->first();

        if (is_null($page)) {
            abort(404, 'The requested page does not exist.');
        }

        return $page;
    }
}
