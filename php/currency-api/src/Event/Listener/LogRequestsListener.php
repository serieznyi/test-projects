<?php


namespace App\Event\Listener;


use App\Service\RequestLoggerService;
use function Logging\logException;
use yii\base\Event;

class LogRequestsListener
{
    /**
     * @var RequestLoggerService
     */
    private $requestLoggerService;

    public function __construct(RequestLoggerService $requestLoggerService)
    {
        $this->requestLoggerService = $requestLoggerService;
    }

    public function __invoke(Event $event): void
    {
        try {
            $this->requestLoggerService->logCurrentRequest();
        } catch (\Throwable $exception) {
            logException($exception, __METHOD__);
        }
    }
}