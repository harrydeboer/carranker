<?php

declare(strict_types=1);

class Package
{
    private $paths;

    private function getDirContents(string $dir)
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if ($value != "." && $value != "..") {
                if (is_dir($path)) {
                    $this->getDirContents($path);
                }
                if (substr($path, -4, 4) === '.php') {
                    $this->paths[] = $path;
                }
            }
        }
    }

    public function readPackage(string $path)
    {
        $this->getDirContents(dirname(__DIR__) . '/vendor/' . $path);
        $classes = $this->paths;
        $this->paths = [];

        if (isset($interfaces)) {
            $this->tryLoad($interfaces);
        }

        if (isset($classes)) {
            $this->tryLoad($classes);
        }
    }

    private function tryLoad(array $paths)
    {
        foreach ($paths as $path) {
            try {
                require $path;
            } catch (Error $e) {
                $requireLater[] = $path;
                continue;
            }
        }

        if (isset($requireLater)) {
            $this->tryLoad($requireLater);
        }
    }
}
