<?php

declare(strict_types=1);

namespace App\Repositories\MySQL;

use App\Models\MySQL\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private Menu $menu,
    ) {
    }

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
