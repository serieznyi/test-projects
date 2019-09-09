<?php
declare(strict_types=1);

namespace App\Helper;

/**
 * Class ArrayHelper
 * @package App\Helper
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * Упорядочивание массива по ключу.
     *
     * @param array $data
     * @param $key
     * @param int $type
     *
     * @return void
     */
    public static function sortByKey(array &$data, $key, $type = SORT_ASC): void
    {
        if ($type === SORT_ASC) {
            \usort($data, function ($a, $b) use ($key) {
                return $a[$key] <=> $b[$key];
            });
        } elseif ($type === SORT_DESC) {
            \usort($data, function ($a, $b) use ($key) {
                return $b[$key] <=> $a[$key];
            });
        }
    }
}
