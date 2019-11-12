<?php

namespace App\Form;

use App\Persistence\Database;

class RegistrationForm extends Form
{
    private static $passwordRegexAtLeastOneLetterAndOneNumeric = '/[0-9]*[a-zA-Z]+[0-9a-zA-Z]*/';

    public $login;
    public $email;
    public $password;
    public $passwordConfirm;
    /**
     * @var Database
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;

        $this->registerRules();
    }

    public function load($data)
    {
        $this->login = array_key_exists('login', $data) ? $data['login'] : null;
        $this->email = array_key_exists('email', $data) ? $data['email'] : null;
        $this->password = array_key_exists('password', $data) ? $data['password'] : null;
        $this->passwordConfirm = array_key_exists('confirm_password', $data) ? $data['confirm_password'] : null;
    }

    private function registerRules()
    {
        $this->addRule(function() {
            if (empty($this->login)) {
                $this->addError('login', 'Login is empty');
            }
        });

        $this->addRule(function() {
            if (!$this->hasError('login') && !preg_match('/^[0-9a-z]+$/', $this->login)) {
                $this->addError('login', 'Login can contains only letters in lower case and numbers');
            }
        });

        $this->addRule(function() {
            if (!$this->hasError('login') && strlen($this->login) < 5) {
                $this->addError('login', 'To small. Want be 5 or more symbols');
            }
        });

        $this->addRule(function() {
            if (!$this->hasError('login') && $this->database->findUserByLogin($this->login)) {
                $this->addError('login', 'Login already used');
            }
        });

        $this->addRule(function() {
            if (empty($this->email)) {
                $this->addError('email', 'Email is empty');
            }
        });

        $this->addRule(function() {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->addError('email', 'Email is incorrect');
            }
        });

        $this->addRule(function() {
            if (!$this->hasError('email') && $this->database->findUserByEmail($this->email)) {
                $this->addError('email', 'Email already used');
            }
        });

        $this->addRule(function() {
            if (empty($this->password)) {
                $this->addError('password', 'Password is empty');
            }
        });

        $this->addRule(function() {
            if (
                !$this->hasError('password')
                && !preg_match(self::$passwordRegexAtLeastOneLetterAndOneNumeric, $this->password)
            ) {
                $this->addError('password', 'Password must contains at least one char and one number');
            }
        });

        $this->addRule(function() {
            if (empty($this->passwordConfirm)) {
                $this->addError('passwordConfirm', 'Password is empty');
            }
        });

        $this->addRule(function() {
            if ($this->password !== $this->passwordConfirm) {
                $this->addError('passwordConfirm', 'Passwords is not equals');
            }
        });
    }
}