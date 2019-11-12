<?php

namespace App\Web\Filter;

use App\Service\AuthService;

class OnlyLoggedFilter
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
        if (!$this->authService->isAuthorized()) {
            header('Location: /login');
            exit();
        }
    }
}