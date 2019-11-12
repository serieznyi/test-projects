<?php

namespace App\Action\Auth;

use App\Service\AuthService;

/**
 * Class LogoutAction
 * @package App\Action\Auth
 */
class LogoutAction
{
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke()
    {
        $this->authService->logout();

        header('Location: /');
    }
}