<?php declare(strict_types=1);


namespace App\Service;


use Yii;

class RequestDurationResolver
{
    public function resolve(): float
    {
        return Yii::getLogger()->getElapsedTime();
    }
}