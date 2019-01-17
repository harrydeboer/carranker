<?php

declare(strict_types=1);

function fileUrl(string $url): string
{
    $root = dirname(__DIR__);

    if (file_exists($root . '/public' . $url)) {
        return $url . '?' . filemtime($root . '/public' . $url);
    }

    return "";
}
