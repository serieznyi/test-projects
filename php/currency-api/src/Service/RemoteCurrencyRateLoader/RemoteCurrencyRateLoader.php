<?php declare(strict_types=1);


namespace App\Service\RemoteCurrencyRateLoader;

interface RemoteCurrencyRateLoader
{
    /**
     * @return CurrencyRate[]
     */
    public function load(): array;
}