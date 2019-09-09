<?php

declare(strict_types=1);

use function Helpers\getAlias;
use yii\web\JsonParser;

$config = [
    'id' => 'app-api',
    'basePath' => getAlias('@root'),
    'runtimePath' => getAlias('@runtimeGlobal/api'),
    'defaultRoute' => 'default/index',
    'controllerNamespace' => 'App\\Controller\\Api',
    'bootstrap' => [
        'log',
        'eventConfigurator',
    ],
    'components' => [
        'errorHandler' => [
            'class' => \yii\web\ErrorHandler::class,
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'request' => [
            'class' => \yii\web\Request::class,
            'enableCsrfCookie' => false,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'default cookie validation key',
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:[\w-]+>/<action:[\w-]+>' => '<controller>/<action>',
            ],
        ],
    ],
];

return $config;
