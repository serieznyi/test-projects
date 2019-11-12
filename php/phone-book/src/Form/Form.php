<?php

namespace App\Form;

use Closure;

abstract class Form
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @var Closure[]
     */
    private $rules = [];

    /**
     * @param array $data
     * @return void
     */
    abstract public function load($data);

    /**
     * @return bool
     */
    public function validate() {
        $this->errors = [];

        foreach ($this->rules as $rule) {
            try {
                $rule($this);
            } catch (\Exception $exception) {
                // LOG ERROR
                $this->addError('_internal', $exception->getMessage());
            } catch (\Throwable $exception) {
                // LOG ERROR
                $this->addError('_internal', $exception->getMessage());
            }
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors() {
        return [] !== $this->errors;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function hasError($field) {
        return !empty($this->errors[$field]);
    }

    /**
     * @param string $fieldName
     * @param string $error
     */
    public function addError($fieldName, $error) {
        if (!array_key_exists($fieldName, $this->errors)) {
            $this->errors[$fieldName] = [];
        }

        $this->errors[$fieldName][] = $error;
    }

    public function addRule(Closure $rule) {
        $this->rules[] = $rule;
    }
}