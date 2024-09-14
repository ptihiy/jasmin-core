<?php

namespace Jasmin\Core\Validation\Rules;

class Equals implements RuleInterface
{
    public static function validate(mixed $value, array $data, ?string $extra = null): bool
    {
        return array_key_exists($extra, $data) && $value === $data[$extra];
    }

    public static function getMessage(): string
    {
        return "Fields are not equal";
    }
}
