<?php

namespace App\Exception;

use Throwable;

/**
 * Class InvalidUserCredentialsException
 * @package App\Exception
 */
class InvalidUserCredentialsException extends \RuntimeException
{
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;

    /**
     * InvalidUserCredentialsException constructor.
     * @param string $login
     * @param string $password
     */
    public function __construct($login, $password)
    {
        parent::__construct('Invalid login or password', 0);
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}