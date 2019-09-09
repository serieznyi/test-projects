<?php
declare(strict_types=1);

namespace App\Adapter;

use yii\caching\Cache;

/**
 * @package App\Adapter
 */
class CacheAdapter extends Cache
{
    /**
     * @inheritdoc
     */
    protected function getValue($key)
    {
        // TODO: Implement getValue() method.
    }

    /**
     * @inheritdoc
     */
    protected function setValue($key, $value, $duration)
    {
        // TODO: Implement setValue() method.
    }

    /**
     * @inheritdoc
     */
    protected function addValue($key, $value, $duration)
    {
        // TODO: Implement addValue() method.
    }

    /**
     * @inheritdoc
     */
    protected function deleteValue($key)
    {
        // TODO: Implement deleteValue() method.
    }

    /**
     * @inheritdoc
     */
    protected function flushValues()
    {
        // TODO: Implement flushValues() method.
    }
}
