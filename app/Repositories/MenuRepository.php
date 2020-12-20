<?php

namespace App\Repositories;

use App\Models\Menu;
use Exception;
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

    public function find(int $id): ?Menu
    {
        return Menu::find($id);
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

    /** The menus from the cms have to be synced with the database. First the menus are created when not present in the
     * database. Then the menu pages have to be synced with the many to many table menus_pages.
     * Lastly the menus that are not in the cms are deleted.
     * When there is an update or delete the cache must be flushed.
     * @throws Exception
     */
    public function syncMenusWithCMS(object $menusCMS)
    {
        if (!isset($menusCMS->navigationHeader) || empty($menusCMS->navigationHeader) ||
            !isset($menusCMS->navigationFooter) || empty($menusCMS->navigationFooter)) {
            throw new Exception("Error: Necessary menu(s)/menuitem(s) deleted.");
        }

        $menusDB = $this->all();

        $namesDB = [];
        foreach ($menusDB as $menu) {
            $namesDB[] = $menu->getName();
        }

        $namesCMS = [];
        foreach ($menusCMS as $menuName => $menuCMS) {
            if (!in_array($menuName, $namesDB)) {
                $this->create([
                    'name' => $menuName,
                ]);
                $namesDB[] = $menuName;
            }
            $namesCMS[] = $menuName;
            $ids = [];
            foreach ($menuCMS as $item) {
                $ids[] = $this->pageRepository->getByName($item->title)->getId();
            }
            $this->getByName($menuName)->getPages()->sync($ids);
        }

        $deletes = array_diff($namesDB, $namesCMS);
        foreach ($deletes as $deleteName) {
            $menu = $this->getByName($deleteName);
            $this->delete($menu->getId());
        }
    }
}
