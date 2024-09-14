<?php

namespace Jasmin\Core\Console\Commands;

class ClearCacheCommand implements CommandInterface
{
    public function handle(): void
    {
        $cachePath = realpath(dirname($_SERVER['PHP_SELF'])) . '/cache';

        $cacheDir = dir($cachePath);

        $count = 0;

        while (($file = $cacheDir->read()) !== false) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            unlink($cachePath . '/' . $file);
            $count++;
        }

        echo "Cache cleared. $count files deleted.";
    }
}
