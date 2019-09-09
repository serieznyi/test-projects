<?php


namespace App\Service\Currency\Converter;


use App\Exception\UnknownCurrencyPairException;
use App\Model\CurrencyExchangeRate;

class DefaultCurrencyConverter implements CurrencyConverter
{
    public const DEFAULT_SOURCE_CURRENCY_CODE = 'USD';

    public function convert(string $source, string $target, float $value): float
    {
        $exchangeRate = CurrencyExchangeRate::findOne([
            'source' => $source,
            'target' => $target
        ]);

        if (!$exchangeRate) {
            throw UnknownCurrencyPairException::createDefault($target, $source);
        }

        return $exchangeRate->coefficient * $value;
    }
}