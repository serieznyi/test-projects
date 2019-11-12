<?php

namespace App\Action\Auth;

use App\Form\RegistrationForm;
use App\Persistence\Database;
use App\Service\AuthService;
use App\View;

class RegisterAction
{
    /**
     * @var View
     */
    private $view;
    /**
     * @var Database
     */
    private $database;
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(View $view, Database $database, AuthService $authService)
    {
        $this->view = $view;
        $this->database = $database;
        $this->authService = $authService;
    }

    public function __invoke()
    {
        $form = new RegistrationForm($this->database);
        $form->load($_POST);

        if ($form->validate()) {
            $this->authService->register($form);
            header('Location: /login');
            exit();
        }

        return $this->view->render('page/register', [
            'form' => $form
        ]);
    }
}