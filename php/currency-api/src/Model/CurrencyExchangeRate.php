<?php declare(strict_types=1);


namespace App\Model;

use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * @property string $id [integer]  Идентификатор
 * @property string $source [varchar(255)]  Исходная валюта
 * @property string $target [varchar(255)]  Конечная валюта
 * @property string $coefficient [varchar(255)]  Курс
 * @property int $created_date [timestamp(0)]  Дата создания
 * @property int $updated_date [timestamp(0)]  Дата обновления
 */
final class CurrencyExchangeRate extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'currency_exchange_rate';
    }

    public function rules(): array
    {
        return [
            ['source', RequiredValidator::class],
            ['source', StringValidator::class],

            ['target', RequiredValidator::class],
            ['target', StringValidator::class],

            ['coefficient', RequiredValidator::class],
            ['coefficient', NumberValidator::class, 'min' => 0],
        ];
    }
}