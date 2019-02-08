<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CmsController extends Controller
{
    public function view(string $url): Response
    {
        $session = session();
        $makename = $session->get('makename');
        $modelname = $session->get('modelname');
        $cacheString = $url . $makename . $modelname;
        $user = Auth::user();
        $cacheString = is_null($user) ? $cacheString : $cacheString . 'auth';

        if ($this->redis->get($cacheString) !== false && is_null($session->get('aspects'))) {

            return response($this->redis->get($cacheString), 200);
        }

        $this->decorator();
        $page = $this->pageRepository->getByName($url);

        $response = response()->view('cms.index', [
            'controller' => 'cms',
            'title' => $page->title,
            'page' => $page,
        ],200);

        $this->redis->set($cacheString, $response->getContent(), $this->cacheExpire);

        return $response;
    }
}