<?php

declare(strict_types = 1);

use App\Controller\Console\Controller;
use App\Logging\Target\StreamTarget;
use function Helpers\getAlias;

$config = [
    'id' => 'app-console',
    'basePath' => getAlias('@root'),
    'runtimePath' => getAlias('@runtimeGlobal/console'),
    'controllerMap' => [
        'app' => Controller::class,
    ],
    'bootstrap' => [
        'log',
    ],
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => StreamTarget::class,
                    'logVars' => [],
                    'except' => ['yii\\*'],
                ],
            ],
        ],
    ],
];

return $config;
