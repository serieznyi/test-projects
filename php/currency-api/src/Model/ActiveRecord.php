<?php
declare(strict_types=1);

namespace App\Model;

use yii\behaviors\TimestampBehavior;

/**
 * Class ActiveRecord
 * @package App\Model
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_date',
                'updatedAtAttribute' => 'updated_date',
                'value' => function () {
                    return date(DATE_ATOM);
                },
            ],
        ];
    }
}
