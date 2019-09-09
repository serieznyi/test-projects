<?php


namespace App\Logging\Target;


use Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\log\Logger;
use yii\log\Target;

/**
 * Implements log target for php stream wrappers (http://php.net/manual/ru/wrappers.php).
 * @package Dooglys\Yii2\Core\Log\Target
 */
class StreamTarget extends Target
{
    public const STREAM_STDOUT = 'php://stdout';

    public const STREAM_STDERR = 'php://stderr';

    public $stream = self::STREAM_STDOUT;

    private $_resource ;

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (empty($this->stream)) {
            throw new InvalidConfigException('No stream configured.');
        }

        if (($this->_resource = @fopen($this->stream, 'wb')) === false) {
            throw new InvalidConfigException("Unable to append to '{$this->stream}'");
        }
    }

    /**
     * @inheritdoc
     */
    public function export(): void
    {
        foreach ($this->messages as $message) {
            fwrite($this->_resource, $this->formatMessage($message) . PHP_EOL);
        }
    }

    /**
     * @inheritdoc
     */
    public function formatMessage($message)
    {
        [$text, $level, $category, $timestamp] = $message;
        $level = Logger::getLevelName($level);

        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof Exception) {
                $text = (string) $text;
            } else {
                $text = Json::encode($text);
            }
        }
        $prefix = $this->getMessagePrefix($message);

        $date = date('Y-m-d H:i:s');

        return "$date {$prefix}[$level][$category] $text";
    }

    /**
     * @inheritdoc
     */
    protected function getContextMessage(): string
    {
        $context = [];

        foreach ($this->logVars as $name) {
            if (!empty($GLOBALS[$name])) {
                $context[] = "\${$name} = " . Json::encode($GLOBALS[$name]);
            }
        }

        return implode("\t", $context);
    }
}
