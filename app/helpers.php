<?php

declare(strict_types=1);

/** Files in the views have to get a modification timestamp. This breaks caching when the file has been changed. */
function fileUrl(string $url): string
{
    $root = dirname(__DIR__);

    if (file_exists($root . '/public' . $url)) {
        return $url . '?' . filemtime($root . '/public' . $url);
    }

    if (!strpos($url, '/img')) {
        throw new \Illuminate\Contracts\Filesystem\FileNotFoundException('The file with url ' . $url . ' does not exist.');
    }

    return "";
}
