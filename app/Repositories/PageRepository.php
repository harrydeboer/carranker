<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository extends BaseRepository
{
    public function getByName(string $name): Page
    {
        $result = Page::where('name', $name)->first();

        if (is_null($result)) {
            abort(404, "The requested page does not exist.");
        }

        return $result;
    }

    public function syncPagesWithCMS(array $pagesCMS): string
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

        if (!in_array('login', $namesCMS) || !in_array('register', $namesCMS) ||
            !in_array('contact', $namesCMS) || !in_array('home', $namesCMS) ||
            !in_array('phpinfo', $namesCMS) || !in_array('opcachereset', $namesCMS)) {
            return "Error: Necessary page(s) deleted.";
        }

        $creates = array_diff($namesCMS, $namesDB);
        foreach ($pagesCMS as $pageCMS) {
            if (in_array($pageCMS->slug, $creates)) {
                $this->create([
                    'name' => $pageCMS->slug,
                    'content' => $pageCMS->content->rendered,
                    'title' => $pageCMS->title->rendered,
                ]);
            } else {
                $page = $this->getByName($pageCMS->slug);
                $page->setContent($pageCMS->content->rendered);
                $page->setTitle($pageCMS->title->rendered);
                $page->update();
            }
        }

        $deletes = array_diff($namesDB, $namesCMS);
        foreach ($deletes as $deleteName) {
            $page = $this->getByName($deleteName);
            $this->delete($page->getId());
        }

        return "";
    }
}
