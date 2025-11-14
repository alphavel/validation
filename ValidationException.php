<?php

namespace Alphavel\Validation;

class ValidationException extends \Exception
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        $message = json_encode($errors);
        parent::__construct($message, 422);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
