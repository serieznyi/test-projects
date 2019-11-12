<?php

namespace App\Action\Book;

use App\Service\PhoneService;
use App\View;

/**
 * Class IndexAction
 * @package App\Action\Book
 */
class IndexAction
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
     * @var string
     */
    private $imagesRoot;

    /**
     * IndexAction constructor.
     * @param View $view
     * @param PhoneService $phoneService
     * @param string $imagesRoot
     */
    public function __construct(View $view, PhoneService $phoneService, $imagesRoot)
    {
        $this->view = $view;
        $this->phoneService = $phoneService;
        $this->imagesRoot = $imagesRoot;
    }

    public function __invoke()
    {
        return $this->view->render('page/phone/index', [
            'phones' => $this->phoneService->findAllPhones(),
            'imagesRoot' => $this->imagesRoot,
        ]);
    }
}