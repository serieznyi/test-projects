<?php

use App\Infrastructure\Application;
use App\Application\Web\Action\DataAction;
use App\Infrastructure\Web\ExceptionHandler;
use App\Infrastructure\Web\ProcessorArgsParser;
use App\Infrastructure\Web\RouteParser;
use App\Infrastructure\Web\WebResponseRenderer;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../bootstrap.php';

/** @var callable $exceptionHandler */
$exceptionHandler = new ExceptionHandler;

set_exception_handler([$exceptionHandler, 'handle']);

$app = new Application(
    require __DIR__ . '/../dependencies.php',
    RouteParser::class,
    ProcessorArgsParser::class,
    WebResponseRenderer::class
);

$app->addRoute('/get_table_data/', DataAction::class);

$app->run();