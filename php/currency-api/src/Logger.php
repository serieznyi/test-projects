<?php declare(strict_types=1);


namespace App;


use Psr\Log\LogLevel;
use Throwable;
use Yii;
use yii\log\Logger as YiiLogger;

class Logger
{
    /**
     * @var array
     */
    private static $levelMap = [
        LogLevel::ERROR => YiiLogger::LEVEL_ERROR,
        LogLevel::EMERGENCY => YiiLogger::LEVEL_ERROR,
        LogLevel::ALERT => YiiLogger::LEVEL_ERROR,
        LogLevel::CRITICAL => YiiLogger::LEVEL_ERROR,
        LogLevel::WARNING => YiiLogger::LEVEL_WARNING,
        LogLevel::NOTICE => YiiLogger::LEVEL_INFO,
        LogLevel::INFO => YiiLogger::LEVEL_INFO,
        LogLevel::DEBUG => YiiLogger::LEVEL_TRACE,
    ];

    /**
     * @var null|string
     */
    protected $category;

    /**
     * Logger constructor.
     *
     * @param null|string $category
     */
    public function __construct(?string $category = 'application')
    {
        $this->category = $category;
    }

    /**
     * {@inheritDoc}
     */
    public function emergency($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_ERROR, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function alert($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_ERROR, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function critical($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_ERROR, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function error($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_ERROR, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function warning($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_WARNING, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function notice($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_INFO, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function info($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_INFO, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function debug($message, array $context = []): void
    {
        $this->log(YiiLogger::LEVEL_TRACE, $message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, $message, array $context = []): void
    {
        $level = $this->prepareLevel($level);

        $this->category = $context['category'] ?? $this->category;
        $logger = Yii::getLogger();

        $exception = $context['exception'] ?? null;
        unset($context['exception'], $context['category']);

        if ($exception && $exception instanceof Throwable) {
            $logger->log($exception, $level, $this->category);
        } else {
            $message = $this->prepareMessage($message, $context);
            $logger->log($message, $level, $this->category);
        }
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message
     * @param array $context
     *
     * @return string
     */
    protected function interpolate(string $message, array $context = []): string
    {
        $replace = [];

        foreach ($context as $key => $value) {
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = $value;
            }
        }

        return strtr($message, $replace);
    }

    /**
     * @param $level
     *
     * @return int
     */
    protected function prepareLevel($level): int
    {
        if (is_string($level)) {
            $level = array_key_exists($level, self::$levelMap)
                ? self::$levelMap[$level]
                : YiiLogger::LEVEL_INFO;
        }
        return $level;
    }

    private function prepareMessage(string $message, array $context)
    {
        $message = $this->interpolate($message, $context);

        if (empty($context)) {
            return $message;
        }

        $context['@message'] = $message;

        return $context;
    }
}