<?php

namespace Jasmin\Core\Validation\Rules;

class Email implements RuleInterface
{
    public static function validate(mixed $value, array $data, ?string $extra = null): bool
    {
        if (!is_null($value)) {
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        } else {
            return true;
        }
    }

    public static function getMessage(): string
    {
        return "The email is not valid";
    }
}
