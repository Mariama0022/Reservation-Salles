<?php

declare(strict_types=1);

namespace App\Validation;

class ValidationResult
{
    public function __construct(
        private bool $valid,
        private array $errors,
        private array $data
    ) {
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->data;
    }
}
