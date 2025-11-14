<?php

namespace Alphavel\Validation;

class Validator
{
    private array $errors = [];

    private array $data = [];

    public function validate(array $data, array $rules): bool
    {
        $this->data = $data;
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $ruleArray = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;

            foreach ($ruleArray as $rule) {
                $this->applyRule($field, $data[$field] ?? null, $rule);
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        [$ruleName, $params] = $this->parseRule($rule);

        match ($ruleName) {
            'required' => $this->validateRequired($field, $value),
            'email' => $this->validateEmail($field, $value),
            'min' => $this->validateMin($field, $value, $params),
            'max' => $this->validateMax($field, $value, $params),
            'numeric' => $this->validateNumeric($field, $value),
            'integer' => $this->validateInteger($field, $value),
            'alpha' => $this->validateAlpha($field, $value),
            'alphanumeric' => $this->validateAlphanumeric($field, $value),
            'in' => $this->validateIn($field, $value, $params),
            'confirmed' => $this->validateConfirmed($field, $value),
            'url' => $this->validateUrl($field, $value),
            'date' => $this->validateDate($field, $value),
            'boolean' => $this->validateBoolean($field, $value),
            'array' => $this->validateArray($field, $value),
            'regex' => $this->validateRegex($field, $value, $params),
            default => null
        };
    }

    private function parseRule(string $rule): array
    {
        $parts = explode(':', $rule, 2);
        $ruleName = $parts[0];
        $params = isset($parts[1]) ? explode(',', $parts[1]) : [];

        return [$ruleName, $params];
    }

    private function validateRequired(string $field, mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->addError($field, "$field is required");
        }
    }

    private function validateEmail(string $field, mixed $value): void
    {
        if ($value && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "$field must be a valid email");
        }
    }

    private function validateMin(string $field, mixed $value, array $params): void
    {
        $min = (int) $params[0];

        if (is_string($value) && strlen($value) < $min) {
            $this->addError($field, "$field must be at least $min characters");
        } elseif (is_numeric($value) && $value < $min) {
            $this->addError($field, "$field must be at least $min");
        }
    }

    private function validateMax(string $field, mixed $value, array $params): void
    {
        $max = (int) $params[0];

        if (is_string($value) && strlen($value) > $max) {
            $this->addError($field, "$field must not exceed $max characters");
        } elseif (is_numeric($value) && $value > $max) {
            $this->addError($field, "$field must not exceed $max");
        }
    }

    private function validateNumeric(string $field, mixed $value): void
    {
        if ($value && ! is_numeric($value)) {
            $this->addError($field, "$field must be numeric");
        }
    }

    private function validateInteger(string $field, mixed $value): void
    {
        if ($value && ! filter_var($value, FILTER_VALIDATE_INT)) {
            $this->addError($field, "$field must be an integer");
        }
    }

    private function validateAlpha(string $field, mixed $value): void
    {
        if ($value && ! ctype_alpha($value)) {
            $this->addError($field, "$field must contain only letters");
        }
    }

    private function validateAlphanumeric(string $field, mixed $value): void
    {
        if ($value && ! ctype_alnum($value)) {
            $this->addError($field, "$field must contain only letters and numbers");
        }
    }

    private function validateIn(string $field, mixed $value, array $params): void
    {
        if ($value && ! in_array($value, $params)) {
            $this->addError($field, "$field must be one of: " . implode(', ', $params));
        }
    }

    private function validateConfirmed(string $field, mixed $value): void
    {
        $confirmField = $field . '_confirmation';

        if ($value !== ($this->data[$confirmField] ?? null)) {
            $this->addError($field, "$field confirmation does not match");
        }
    }

    private function validateUrl(string $field, mixed $value): void
    {
        if ($value && ! filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, "$field must be a valid URL");
        }
    }

    private function validateDate(string $field, mixed $value): void
    {
        if ($value && ! strtotime($value)) {
            $this->addError($field, "$field must be a valid date");
        }
    }

    private function validateBoolean(string $field, mixed $value): void
    {
        if ($value !== null && ! is_bool($value) && ! in_array($value, [0, 1, '0', '1', 'true', 'false'], true)) {
            $this->addError($field, "$field must be boolean");
        }
    }

    private function validateArray(string $field, mixed $value): void
    {
        if ($value && ! is_array($value)) {
            $this->addError($field, "$field must be an array");
        }
    }

    private function validateRegex(string $field, mixed $value, array $params): void
    {
        if ($value && ! preg_match($params[0], $value)) {
            $this->addError($field, "$field format is invalid");
        }
    }

    private function addError(string $field, string $message): void
    {
        if (! isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function getError(string $field): ?array
    {
        return $this->errors[$field] ?? null;
    }

    public function firstError(): ?string
    {
        if (empty($this->errors)) {
            return null;
        }

        $firstField = array_key_first($this->errors);

        return $this->errors[$firstField][0] ?? null;
    }

    public function fails(): bool
    {
        return ! empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public static function make(array $data, array $rules): self
    {
        $validator = new self();
        $validator->validate($data, $rules);

        return $validator;
    }
}
