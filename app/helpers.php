<?php

declare(strict_types=1);

function fileUrl($url)
{
    $root = dirname(__DIR__);

    return $url . '?' . filemtime($root . '/public' . $url);
}

/** Dummy function used for sqlite testing. */
if (!defined('is_multisite')) {
    function is_multisite()
    {
        return false;
    }
}