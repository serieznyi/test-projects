<?php declare(strict_types=1);

namespace App\Application\Web\Action;

use App\Application\Form\DataForm;
use App\Application\Service\DataService;
use App\Application\Web\ResponseFactory;
use App\Infrastructure\Exception\ArrayOfMessagesException;
use App\Infrastructure\Persistence\Exception\UnknownColumnDatabaseException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DataAction
{
    private DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws JsonException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new DataForm();
        $form->load($request->getQueryParams());

        if (!$form->validate()) {
            return ResponseFactory::createErrorValidationResponse($form->getErrors());
        }

        try {
            $data = $this->dataService->findData($form);
        } catch (ArrayOfMessagesException $exception) {
            return ResponseFactory::createErrorValidationResponse($exception->toArrayOfMessages());
        }

        return ResponseFactory::createSuccessResponse($data);
    }
}