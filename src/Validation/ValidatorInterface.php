<?php

namespace Jasmin\Core\Validation;

interface ValidatorInterface
{
    public function validate(array $rules, array $data);
}
