<?php

namespace Jasmin\Core\Validation;

use InvalidArgumentException;
use Jasmin\Core\Validation\Rules\Email;
use Jasmin\Core\Validation\Rules\Equals;
use Jasmin\Core\Validation\Rules\Required;
use Jasmin\Core\Validation\Rules\Unique;

class Validator implements ValidatorInterface
{
    private array $rules = [
        'required' => Required::class,
        'email' => Email::class,
        'equals' => Equals::class,
        'unique' => Unique::class
    ];

    public function validate(array $rules, array $data)
    {
        $errors = [];

        foreach ($rules as $inputName => $inputRules) {
            $input = $data[$inputName];
            foreach (explode('|', $inputRules) as $ruleId) {
                $ruleIdParts = explode(':', $ruleId);
                $rule = $this->getRuleClass($ruleIdParts[0]);
                if (count($ruleIdParts) == 2) {
                    if (!$rule::validate($input, $data, $ruleIdParts[1])) {
                        $errors[$inputName][] = $rule::getMessage();
                    }
                } else {
                    if (!$rule::validate($input, $data)) {
                        $errors[$inputName][] = $rule::getMessage();
                    }
                }
            }
        }

        return $errors;
    }

    private function getRuleClass(string $id): string
    {
        if (array_key_exists($id, $this->rules)) {
            return $this->rules[$id];
        } else {
            throw new InvalidArgumentException("No such rule: " . $id);
        }
    }
}
