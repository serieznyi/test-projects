<?php
declare(strict_types=1);

use App\Adapter\CacheAdapter;
use App\Adapter\CurrentUserAdapter;
use App\Adapter\DbAdapter;
use App\Adapter\RequestAdapter;
use App\Adapter\ResponseAdapter;
use App\Logger;
use App\Params;
use App\Service\RemoteCurrencyRateLoader\DefaultRemoteCurrencyLoader;
use App\Service\RemoteCurrencyRateLoader\RemoteCurrencyRateLoader;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use Psr\Log\LoggerInterface;
use yii\di\Container;
use function Helpers\createVirtualProxy;

$container = [
    'definitions' => [
        LoggerInterface::class => Logger::class,
        RequestAdapter::class => function (Container $container) {
            $factory = $container->get(LazyLoadingValueHolderFactory::class);

            return createVirtualProxy($factory, RequestAdapter::class, Yii::$app->request);
        },
        ResponseAdapter::class => function (Container $container) {
            $factory = $container->get(LazyLoadingValueHolderFactory::class);

            return createVirtualProxy($factory, ResponseAdapter::class, Yii::$app->response);
        },
        CacheAdapter::class => function (Container $c) {
            $factory = $c->get(LazyLoadingValueHolderFactory::class);

            return createVirtualProxy($factory, CacheAdapter::class, Yii::$app->cache);
        },
    ],
    'singletons' => [
        RemoteCurrencyRateLoader::class => DefaultRemoteCurrencyLoader::class,
        RequestAdapter::class,
        ResponseAdapter::class,
    ],
];

return ['container' => $container];
