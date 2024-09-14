<?php

namespace Jasmin\Core\Helpers\Filesystem;

class Dir
{
    public static function files(string $dirPath, bool $absPath = false): array
    {
        $relPaths = array_filter(scandir($dirPath), fn ($e) => !is_dir(sprintf("%s/%s", $dirPath, $e)));

        if (!$absPath) {
            return $relPaths;
        } else {
            return array_map(fn ($e) => sprintf("%s/%s", $dirPath, $e), $relPaths);
        }
    }
}
