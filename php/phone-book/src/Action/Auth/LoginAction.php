<?php

namespace App\Action\Auth;

use App\Exception\InvalidUserCredentialsException;
use App\Form\LoginForm;
use App\Service\AuthService;
use App\View;

class LoginAction
{
    /**
     * @var View
     */
    private $view;
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(View $view, AuthService $authService)
    {
        $this->view = $view;
        $this->authService = $authService;
    }

    public function __invoke()
    {
        $form = new LoginForm();
        $form->load($_POST);

        if ($form->validate()) {
            try {
                $this->authService->login($form);
                header('Location: /');
                exit();
            } catch (InvalidUserCredentialsException $e) {
                $form->addError('login', $e->getMessage());
                $form->addError('password', $e->getMessage());
            }

        }

        return $this->view->render('page/login', [
            'form' => $form
        ]);
    }
}