<?php

namespace App\Action\Book;

use App\Form\BookForm;
use App\Service\PhoneService;
use App\View;
use App\Web\Request;

/**
 * Class AddAction
 * @package App\Action\Book
 */
class AddAction
{
    /**
     * @var View
     */
    private $view;
    /**
     * @var PhoneService
     */
    private $phoneService;
    /**
     * @var BookForm
     */
    private $form;
    /**
     * @var string
     */
    private $tmpImageRootPath;

    /**
     * AddAction constructor.
     * @param View $view
     * @param PhoneService $phoneService
     * @param BookForm $form
     * @param string $tmpImageRootPath
     */
    public function __construct(View $view, PhoneService $phoneService, BookForm $form, $tmpImageRootPath)
    {
        $this->view = $view;
        $this->phoneService = $phoneService;
        $this->form = $form;
        $this->tmpImageRootPath = $tmpImageRootPath;
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function __invoke($request)
    {
        $this->form->load($request->getData());

        if ($request->isPost() && $this->form->validate()) {
            $this->phoneService->add($this->form);
            header('Location: /');
            exit();
        }

        return $this->view->render('page/phone/add', [
            'tmpImageDir' => $this->tmpImageRootPath,
            'form' => $this->form
        ]);
    }
}