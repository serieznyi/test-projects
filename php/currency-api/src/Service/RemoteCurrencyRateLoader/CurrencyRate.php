<?php declare(strict_types=1);


namespace App\Service\RemoteCurrencyRateLoader;

class CurrencyRate
{
    /**
     * @var string
     */
    private $sourceCurrency;
    /**
     * @var string
     */
    private $targetCurrency;
    /**
     * @var float
     */
    private $coefficient;

    public function __construct(string $sourceCurrency, string $targetCurrency, float $coefficient)
    {
        $this->sourceCurrency = $sourceCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->coefficient = $coefficient;
    }

    /**
     * @return string
     */
    public function getSourceCurrency(): string
    {
        return $this->sourceCurrency;
    }

    /**
     * @return string
     */
    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }

    /**
     * @return float
     */
    public function getCoefficient(): float
    {
        return $this->coefficient;
    }
}