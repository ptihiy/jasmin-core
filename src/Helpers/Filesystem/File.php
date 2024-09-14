<?php

namespace Jasmin\Core\Helpers\Filesystem;

use InvalidArgumentException;
use stdClass;

class File
{
    public static function read(string $path): string
    {
        return file_get_contents($path);
    }

    public static function instantiateClassFromFile(string $path)
    {
        if (!File::exists($path)) {
            throw new InvalidArgumentException('Migration file does not exist');
        }

        $contents = File::read($path);

        $ns = File::getNamespace($contents);

        var_dump($ns);
        die();
    }

    public static function exists(string $path): bool
    {
        return file_exists($path);
    }

    private static function getNamespace(string $contents): string
    {
        return preg_match('~namespace [^;];~', $contents);
    }
}
