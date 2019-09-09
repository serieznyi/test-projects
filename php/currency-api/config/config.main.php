<?php

declare(strict_types=1);

use App\EventConfigurator;
use yii\caching\FileCache;
use yii\db\Connection;
use yii\i18n\Formatter;
use yii\log\FileTarget;
use function Helpers\getenv;

$config = [
    'sourceLanguage' => 'ru-RU',
    'language' => 'ru-RU',
    'vendorPath' => __DIR__ . '/../vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
            'cachePath' => '@runtimeGlobal/cache'
        ],
        'eventConfigurator' => [
            'class' => EventConfigurator::class,
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 0,
            'enableProfiling' => getenv('DB_ENABLE_PROFILING'),
            'enableLogging' => getenv('DB_ENABLE_LOGGING'),
        ],
        'formatter' => [
            'class' => Formatter::class,
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/app.log',
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:403',
                        'yii\web\HttpException:401',
                        'yii\web\HttpException:400',
                        'yii\web\HttpException:503',
                    ],
                ],
            ],
        ],
    ],
];

return $config;
