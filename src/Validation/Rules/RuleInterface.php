<?php

namespace Jasmin\Core\Validation\Rules;

interface RuleInterface
{
    public static function validate(mixed $value, array $data, ?string $extra = null): bool;

    public static function getMessage(): string;
}
