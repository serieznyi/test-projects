<?php

namespace App\Infrastructure;

use Closure;
use Exception;
use Throwable;

abstract class Form
{
    /**
     * @var array
     */
    private array $errors;

    /**
     * @var Closure[]
     */
    private array $rules = [];

    abstract public function load(array $data): void;

    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->rules as $rule) {
            try {
                $rule($this);
            } catch (Exception $exception) {
                // LOG ERROR
                $this->addError('_internal', $exception->getMessage());
            } catch (Throwable $exception) {
                // LOG ERROR
                $this->addError('_internal', $exception->getMessage());
            }
        }

        return empty($this->errors);
    }

    public function addError(string $fieldName, string $error): void
    {
        if (!array_key_exists($fieldName, $this->errors)) {
            $this->errors[$fieldName] = [];
        }

        $this->errors[$fieldName][] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return [] !== $this->errors;
    }

    public function hasError($field): bool
    {
        return !empty($this->errors[$field]);
    }

    public function addRule(Closure $rule): void
    {
        $this->rules[] = $rule;
    }
}