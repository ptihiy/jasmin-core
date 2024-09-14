<?php

namespace Jasmin\Core\Auth;

class Crypto
{
    public static function hash(string $password): string
    {
        return hash('sha256', $password);
    }
}
