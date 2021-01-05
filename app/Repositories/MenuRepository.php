<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository implements IRepository
{
    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function all(): Collection
    {
        return Menu::all();
    }

    public function get(int $id): Menu
    {
        return Menu::findOrFail($id);
    }

    public function create(array $createArray): Menu
    {
        $model = new Menu($createArray);
        $model->save();

        return $model;
    }

    public function delete(int $id): void
    {
        Menu::destroy($id);
    }

    public function getByName(string $name): Menu
    {
        return Menu::where('name', $name)->first();
    }
}
