<?php

namespace App\Service;

use App\Exception\InvalidUserCredentialsException;
use App\Form\LoginForm;
use App\Form\RegistrationForm;
use App\Persistence\Database;
use App\Web\Util\AuthUtil;

class AuthService
{
    /**
     * @var Database
     */
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function register(RegistrationForm $form) {
        $this->db->insertUser(
            $form->email,
            $form->login,
            $this->cryptPassword($form->password)
        );
    }

    /**
     * @param string $password
     * @return string
     */
    private function cryptPassword($password)
    {
        return sha1($password);
    }

    public function login(LoginForm $form)
    {
        $userData = $this->db->findUserByLoginAndPassword(
            $form->login,
            $this->cryptPassword($form->password)
        );

        if ([] === $userData) {
            throw new InvalidUserCredentialsException($form->login, $form->password);
        }

        $_SESSION['user'] = [
            'id' => $userData['id'],
            'username' => $userData['login']
        ];
    }

    public function isAuthorized() {
        return AuthUtil::isAuthorized();
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

    /**
     * @return int
     */
    public function getUserIdentifier()
    {
        return AuthUtil::getUserId();
    }
}