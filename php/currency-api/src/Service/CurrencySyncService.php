<?php


namespace App\Service;


use App\Exception\DomainException;
use App\Exception\ModelValidationException;
use App\Model\CurrencyExchangeRate;
use App\Service\RemoteCurrencyRateLoader\CurrencyRate;

class CurrencySyncService
{
    public function syncCurrency(CurrencyRate $currencyRate): void
    {
        $uniqueIndex = [
            'source' => $currencyRate->getSourceCurrency(),
            'target' => $currencyRate->getTargetCurrency(),
        ];

        $model = CurrencyExchangeRate::findOne($uniqueIndex);

        if (!$model) {
            $model = new CurrencyExchangeRate([
                'attributes' => $uniqueIndex,
            ]);
        }

        $model->coefficient = $currencyRate->getCoefficient();

        if (!$model->save()) {
            throw ModelValidationException::createDefault($model->getErrors());
        }
    }
}