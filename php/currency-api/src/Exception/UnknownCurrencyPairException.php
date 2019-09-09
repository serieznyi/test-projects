<?php


namespace App\Exception;


use Throwable;

class UnknownCurrencyPairException extends DomainException
{

    /**
     * @var string
     */
    private $sourceCurrency;
    /**
     * @var string
     */
    private $targetCurrency;

    public function __construct(
        string $sourceCurrency,
        string $targetCurrency,
        $message,
        Throwable $previous = null
    ) {
        $this->sourceCurrency = $sourceCurrency;
        $this->targetCurrency = $targetCurrency;

        parent::__construct($message, 0, $previous);
    }

    public static function createDefault(string $source, string $target): self
    {
        return new self(
            $source,
            $target,
            'Не могу конвертировать указанную пару валют'
        );
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
}