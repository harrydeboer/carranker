<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository extends BaseRepository
{
    public function getByName(string $name): Menu
    {
        return Menu::where('name', $name)->first();
    }

    public function syncMenusWithCMS(object $menusCMS): string
    {
        if (!isset($menusCMS->navigationHeader) || empty($menusCMS->navigationHeader) ||
            !isset($menusCMS->navigationFooter) || empty($menusCMS->navigationFooter)) {
            return "Error: Necessary menu(s)/menuitem(s) deleted.";
        }

        $pageRepository = new PageRepository();
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
                $ids[] = $pageRepository->getByName($item->title)->getId();
            }
            $this->getByName($menuName)->getPages()->sync($ids);
        }

        $deletes = array_diff($namesDB, $namesCMS);
        foreach ($deletes as $deleteName) {
            $menu = $this->getByName($deleteName);
            $this->delete($menu->getId());
        }

        return "";
    }
}
