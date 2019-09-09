<?php


namespace App\Model;


use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

/**
 * @property string $id [integer]  Идентификатор
 * @property string $method [varchar(255)]  Тип запроса
 * @property string $uri [varchar(255)]  URL
 * @property string $params [varchar(255)]  Параметры запроса
 * @property string $client_agent [varchar(255)]  Информация о клиенте
 * @property string $client_ip [varchar(255)]  IP адресс
 * @property string $duration [integer]  Длительность запроса
 * @property int $created_date [timestamp(0)]  Дата создания
 */
final class RequestLog extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'request_log';
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_date'],

                ],
                'createdAtAttribute' => 'created_date',
                'value' => function () {
                    return date(DATE_ATOM);
                },
            ],
        ];
    }

    public function rules(): array
    {
        return [
            ['method', RequiredValidator::class],
            ['method', StringValidator::class],

            ['uri', RequiredValidator::class],
            ['uri', StringValidator::class],

            ['client_agent', RequiredValidator::class],
            ['client_agent', StringValidator::class],

            ['client_ip', RequiredValidator::class],
            ['client_ip', StringValidator::class],

            ['params', RequiredValidator::class],
            ['params', SafeValidator::class],

            ['duration', RequiredValidator::class],
            ['duration', NumberValidator::class],
        ];
    }
}