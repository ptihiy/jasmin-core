<?php

namespace Jasmin\Core\Validation\Rules;

class Required implements RuleInterface
{
    public static function validate(mixed $value, array $data, ?string $extra = null): bool
    {
        return !is_null($value);
    }

    public static function getMessage(): string
    {
        return "The value is required";
    }
}
