<?php

use App\Application\Cli\Command\Migrate\UpCommand;
use App\Application\Repository\PdoDataRepository;
use App\Application\Service\DataService;
use App\Application\Web\Action\DataAction;
use App\Domain\Repository\DataRepository;
use App\Infrastructure\Application;
use App\Infrastructure\Cli;
use App\Infrastructure\Cli\CliResponseRenderer;
use App\Infrastructure\Migrate;
use App\Infrastructure\Persistence\Database;
use App\Infrastructure\Web;
use App\Infrastructure\Web\WebResponseRenderer;

$tmpImageDir = __DIR__ . '/public/tmp/';

return [
    Cli\ExceptionHandler::class => static function () {
        return new Cli\ExceptionHandler();
    },
    Cli\RouteParser::class => static function () {
        return new Cli\RouteParser();
    },
    Cli\ProcessorArgsParser::class => static function () {
        return new Cli\ProcessorArgsParser();
    },
    Web\ExceptionHandler::class => static function () {
        return new Web\ExceptionHandler();
    },
    Web\RouteParser::class => static function () {
        return new Web\RouteParser();
    },
    Web\ProcessorArgsParser::class => static function () {
        return new Web\ProcessorArgsParser();
    },
    Migrate::class => static function (Application $application) {
        return new Migrate(
            $application->buildObject(Database::class),
            __DIR__ . '/etc/db/migrations/'
        );
    },
    UpCommand::class => static function (Application $application) {
        return new UpCommand($application->buildObject(Migrate::class));
    },
    Database::class => static function () {
        return Database::instance('mysql:dbname=test;host=mysql;charset=UTF8;', 'test', 'test');
    },
    DataRepository::class => static function (Application $application) {
        return new PdoDataRepository($application->buildObject(Database::class));
    },
    DataAction::class => static function (Application $application) {
        return new DataAction(
            $application->buildObject(DataService::class)
        );
    },
    CliResponseRenderer::class => static function () {
        return new CliResponseRenderer();
    },
    WebResponseRenderer::class => static function () {
        return new WebResponseRenderer();
    },
    DataService::class => static function (Application $application) {
        return new DataService(
            $application->buildObject(DataRepository::class)
        );
    },
];