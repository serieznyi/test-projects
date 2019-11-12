<?php

namespace App\Action\Book;

use App\Service\PhoneService;
use App\Web\Request;

class DeleteAction
{
    /**
     * @var PhoneService
     */
    private $phoneService;

    public function __construct(PhoneService $phoneService)
    {
        $this->phoneService = $phoneService;
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function __invoke($request)
    {
        $params = $request->getParams();

        if ($request->isPost()) {
            $this->phoneService->deleteByIdentifier((int) $params['id']);
        }

        header('Location: /');
        exit();
    }
}