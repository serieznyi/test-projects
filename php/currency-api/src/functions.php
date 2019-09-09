<?php
declare(strict_types=1);

namespace Helpers {

    use ProxyManager\Factory\LazyLoadingValueHolderFactory;
    use ProxyManager\Proxy\LazyLoadingInterface;
    use ProxyManager\Proxy\VirtualProxyInterface;

    function createVirtualProxy(
        LazyLoadingValueHolderFactory $factory,
        string $proxyClassName,
        $object
    ): VirtualProxyInterface {
        $initializer = function (
            &$wrappedObject,
            LazyLoadingInterface $proxy,
            $method,
            array $parameters,
            &$initializer
        ) use ($object) {
            $initializer = null;
            $wrappedObject = $object;

            return true;
        };

        return $factory->createProxy($proxyClassName, $initializer);
    }

    /**
     * Обертка над DI контейнером
     *
     * @param string|array $class
     * @param array $params
     *
     * @throws \yii\base\InvalidConfigException
     *
     * @return object
     */
    function createObject($class, array $params = [])
    {
        return \Yii::createObject($class, $params);
    }

    /**
     * Обертка над \Yii::getAlias.
     *
     * @param string $path
     *
     * @return string
     */
    function getAlias(string $path): string
    {
        return \Yii::getAlias($path);
    }

    /**
     * Извлекает переменную окружения стандартной функцией getenv.
     *
     * Приводит boolean строки к bool
     *
     * @param $key
     * @param bool|mixed $default  значение по умолчанию
     *
     * @return array|false|mixed|string
     */
    function getenv($key, $default = false)
    {
        $value = \getenv($key);

        if ($value === false) {
            return $default;
        }

        if (\is_string($value) && \in_array(\strtolower($value), ['false', 'true'], true)) {
            $value = \filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return $value;
    }
}

namespace Helpers\Translation {
    function t($message, array $params = [], $category = 'app'): string
    {
        return \Yii::t($category, $message, $params);
    }
}

namespace Logging {

    use Psr\Log\LoggerInterface;
    use Psr\Log\LogLevel;
    use function array_merge;
    use function Helpers\createObject;

    /**
     * Обертка над Yii::error.
     *
     * @param string $message
     * @param string|null $category
     * @param array $context
     */
    function logError(string $message, string $category = null, array $context = []): void
    {
        log(LogLevel::ERROR, $message, $category, $context);
    }

    function logException(\Throwable $throwable, string $category = null): void
    {
        log(LogLevel::ERROR, $throwable->getMessage(), $category, ['exception' => $throwable]);
    }

    /**
     * Обертка над Yii::info.
     *
     * @param string $message
     * @param string|null $category
     * @param array $context
     */
    function logInfo(string $message, string $category = null, array $context = []): void
    {
        log(LogLevel::INFO, $message, $category, $context);
    }

    /**
     * Обертка над Yii::debug.
     *
     * @param string $message
     * @param string|null $category
     * @param array $context
     */
    function logDebug(string $message, string $category = null, array $context = []): void
    {
        log(LogLevel::DEBUG, $message, $category, $context);
    }

    /**
     * Обертка над Yii::warning.
     *
     * @param string $message
     * @param string|null $category
     * @param array $context
     */
    function logWarning(string $message, string $category = null, array $context = []): void
    {
        log(LogLevel::WARNING, $message, $category, $context);
    }

    /**
     * Обертка над Yii::log.
     *
     * @param string $level
     * @param string $message
     * @param string|null $category
     * @param array $context
     */
    function log(string $level, string $message, string $category = null, array $context = []): void
    {
        static $logger;

        if ($logger === null) {
            $logger = createObject(LoggerInterface::class);
        }

        $logger->log($level, $message, array_merge($context, ['category' => $category]));
    }
}
