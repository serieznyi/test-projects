<?php

namespace App\Service\Currency\Converter;

interface CurrencyConverter
{
    public function convert(string $source, string $target, float $value): float;
}