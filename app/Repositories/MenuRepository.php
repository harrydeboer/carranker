<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository implements IRepository
{
    public function __construct(
        private PageRepository $pageRepository,
        private Menu $menu,
    ){}

    public function all(): Collection
    {
        return Menu::all();
    }

    public function get(int $id): Menu
    {
        return $this->menu->findOrFail($id);
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

    public function findByName(string $name): ?Menu
    {
        return $this->menu->where('name', $name)->first();
    }
}
