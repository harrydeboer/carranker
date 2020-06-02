<?php

declare(strict_types=1);

class Package
{
    private $files;

    private function getDirContents(string $dir)
    {
        $filesOrDirs = scandir($dir);

        foreach ($filesOrDirs as $fileOrDir) {
            if ($fileOrDir != "." && $fileOrDir != "..") {
                $fullPath = $dir . DIRECTORY_SEPARATOR . $fileOrDir;
                if (is_dir($fullPath)) {
                    $this->getDirContents($fullPath);
                } elseif (substr($fullPath, -4, 4) === '.php') {
                    $this->files[] = $fullPath;
                }
            }
        }
    }

    public function readPackage(string $path)
    {
        $this->getDirContents(dirname(__DIR__) . '/vendor/' . $path);
        $files = $this->files;
        $this->files = [];

        if (isset($files)) {
            $this->tryLoad($files);
        }
    }

    private function tryLoad(array $files)
    {
        foreach ($files as $file) {
            try {
                require $file;
            } catch (Error $e) {
                $requireLater[] = $file;
                continue;
            }
        }

        if (isset($requireLater)) {
            $this->tryLoad($requireLater);
        }
    }
}
