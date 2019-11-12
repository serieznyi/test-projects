<?php

namespace App\Action\Book;

use App\Form\BookForm;
use App\Persistence\Database;
use App\Service\PhoneService;
use App\View;
use App\Web\Request;

class UpdateAction
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
     * @var PhoneService
     */
    private $phoneService;
    /**
     * @var BookForm
     */
    private $form;
    private $imagesRoot;

    /**
     * UpdateAction constructor.
     * @param View $view
     * @param Database $database
     * @param PhoneService $phoneService
     * @param BookForm $form
     * @param string $imagesRoot
     */
    public function __construct(View $view, Database $database, PhoneService $phoneService, BookForm $form, $imagesRoot)
    {
        $this->view = $view;
        $this->database = $database;
        $this->phoneService = $phoneService;
        $this->form = $form;
        $this->imagesRoot = $imagesRoot;
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function __invoke($request)
    {
        $this->fillFromDb($request);

        $this->form->load($request->getData());

        if ($request->isPost() && $this->form->validate()) {
            $id = $request->getParam('id');
            $this->phoneService->update($id, $this->form);
            header('Location: /phone/update/' . $request->getParam('id'));
            exit();
        }

        return $this->view->render('page/phone/update', [
            'form' => $this->form,
            'id' => $request->getParam('id'),
            'imagesRoot' => $this->imagesRoot,
        ]);
    }

    /**
     * @param Request $request
     */
    private function fillFromDb($request)
    {
        $rows = $this->database->findPhoneById($request->getParam('id'));

        $data = [];
        foreach ($rows as $key => $value) {
            $data[$this->underCaseToCamelCase($key)] = $value;
        }

        $this->form->load($data);
    }

    private function underCaseToCamelCase($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}