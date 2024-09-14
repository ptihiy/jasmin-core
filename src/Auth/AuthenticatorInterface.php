<?php

namespace Jasmin\Core\Auth;

interface AuthenticatorInterface
{
    public static function login(string $email, string $password): ?AuthenticatibleInterface;
}
