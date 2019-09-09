<?php


namespace App\Service;


use App\Adapter\RequestAdapter;
use App\Exception\ModelValidationException;
use App\Model\RequestLog;

class RequestLoggerService
{
    /**
     * @var RequestAdapter
     */
    private $requestAdapter;
    /**
     * @var RequestDurationResolver
     */
    private $requestDurationResolver;

    public function __construct(RequestAdapter $requestAdapter, RequestDurationResolver $requestDurationResolver)
    {
        $this->requestAdapter = $requestAdapter;
        $this->requestDurationResolver = $requestDurationResolver;
    }

    public function logCurrentRequest(): void
    {
        $model = new RequestLog();
        $model->setAttributes($this->parseRequestAttributes());

        if (!$model->save()) {
            throw ModelValidationException::createDefault($model->getErrors());
        }
    }

    public function parseRequestAttributes(): array
    {
        return [
            'method' => $this->requestAdapter->getMethod(),
            'uri' => $this->requestAdapter->getUrl(),
            'params' => $this->requestAdapter->getMethod() === 'POST' ?
                $this->requestAdapter->getBodyParams() :
                $this->requestAdapter->getQueryParams(),
            'client_agent' => $this->requestAdapter->getUserAgent(),
            'client_ip' => $this->requestAdapter->getUserIP(),
            'duration' => $this->requestDurationResolver->resolve(),
        ];
    }
}