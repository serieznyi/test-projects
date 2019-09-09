<?php


namespace App\Form\Api;


use App\Form\Form;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class ConvertForm extends Form
{
    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $value;

    public function rules()
    {
        return [
            ['currency', RequiredValidator::class],
            ['currency', StringValidator::class],

            ['value', RequiredValidator::class],
            ['value', NumberValidator::class, 'min' => 1],
        ];
    }
}