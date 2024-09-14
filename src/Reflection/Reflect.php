<?php

namespace Jasmin\Core\Reflection;

use InvalidArgumentException;
use Jasmin\Core\Helpers\Filesystem\File;

class Reflect
{
    public static function createClassFromFile($path): string
    {
        if (!File::exists($path)) {
            throw new InvalidArgumentException("File not found");
        }

        $handle = fopen($path, "r");
        if ($handle) {
            $ns = "";
            $class = "";
            while (($line = fgets($handle)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                }
                if (str_starts_with($line, 'class')) {
                    $parts = explode(' ', $line);
                    $class = rtrim(trim($parts[1]), ';');
                    break;
                }
            }

            fclose($handle);

            include $path;

            $className = $ns . '\\' . $class;

            return $className;
        }
    }
}
