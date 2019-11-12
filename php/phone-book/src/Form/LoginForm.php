<?php

namespace App\Form;

class LoginForm extends Form
{
    public $login;
    public $password;

    public function __construct()
    {
        $this->registerRules();
    }

    public function load($data)
    {
        $this->login = array_key_exists('login', $data) ? $data['login'] : null;
        $this->password = array_key_exists('password', $data) ? $data['password'] : null;
    }

    private function registerRules()
    {
        $this->addRule(function() {
            if (empty($this->login)) {
                $this->addError('login', 'Login is empty');
            }
        });

        $this->addRule(function() {
            if (empty($this->password)) {
                $this->addError('password', 'Password is empty');
            }
        });
    }
}