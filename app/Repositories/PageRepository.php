<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

class PageRepository implements IRepository
{
    public function __construct(
        private Page $page,
    ){}

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
}
