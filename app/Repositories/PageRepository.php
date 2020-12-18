<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PageRepository implements IRepository
{
    public function all(): Collection
    {
        return Page::all();
    }

    public function find(int $id): ?Page
    {
        return Page::find($id);
    }

    public function get(int $id): Page
    {
        return Page::findOrFail($id);
    }

    public function create(array $createArray): Page
    {
        $model = new Page($createArray);
        $model->save();

        return $model;
    }

    public function update(Model $model): void
    {
        $model->save();
    }

    public function delete(int $id): void
    {
        Page::destroy($id);
    }

    public function getByName(string $name): Page
    {
        $result = Page::where('name', $name)->first();

        if (is_null($result)) {
            abort(404, "The requested page does not exist.");
        }

        return $result;
    }

    /** The pages from the cms have to be synced with the database. First the pages are created when not present in the
     * database or updated. Then the pages that are not in the cms are deleted.
     * When data there is an update or delete the cache must be flushed.
     */
    public function syncPagesWithCMS(array $pagesCMS): bool
    {
        $pagesDB = $this->all();

        $namesDB = [];
        foreach ($pagesDB as $page) {
            $namesDB[] = $page->getName();
        }

        $namesCMS = [];
        foreach ($pagesCMS as $pageCMS) {
            $namesCMS[] = $pageCMS->slug;
        }

        /** The phpinfo page must be in the cms, but must not be in the laravel pages table. */
        if (!in_array('auth', $namesCMS) || !in_array('register', $namesCMS) ||
            !in_array('contact', $namesCMS) || !in_array('home', $namesCMS) ||
            !in_array('phpinfo', $namesCMS)) {
            throw new \Exception("Error: Necessary page(s) deleted.");
        }

        $flushCache = false;
        $creates = array_diff($namesCMS, $namesDB);
        foreach ($pagesCMS as $pageCMS) {
        	if ($pageCMS->slug === 'phpinfo') {
        		continue;
	        }
            if (in_array($pageCMS->slug, $creates)) {
                $this->create([
                    'name' => $pageCMS->slug,
                    'content' => $pageCMS->content->rendered,
                    'title' => $pageCMS->title->rendered,
                ]);
                $flushCache = true;
            } else {
                $page = $this->getByName($pageCMS->slug);
                if ($pageCMS->content->rendered !== $page->getContent()) {
                    $flushCache = true;
                }
                $page->setContent($pageCMS->content->rendered);
                $page->setTitle($pageCMS->title->rendered);
                $page->save();
            }
        }

        $deletes = array_diff($namesDB, $namesCMS);
        foreach ($deletes as $deleteName) {
            $page = $this->getByName($deleteName);
            $this->delete($page->getId());
            $flushCache = true;
        }

        return $flushCache;
    }
}
