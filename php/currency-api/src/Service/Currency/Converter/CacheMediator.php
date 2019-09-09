<?php


namespace App\Service\Currency\Converter;


use App\Adapter\CacheAdapter;

class CacheMediator implements CurrencyConverter
{
    /**
     * @var CacheAdapter
     */
    private $cacheAdapter;
    /**
     * @var CurrencyConverter
     */
    private $converter;

    public function __construct(CacheAdapter $cacheAdapter, CurrencyConverter $converter)
    {
        $this->cacheAdapter = $cacheAdapter;
        $this->converter = $converter;
    }

    public function convert(string $source, string $target, float $value): float
    {
        $key = "$source:$target";

        $result = $this->cacheAdapter->get($key);

        if ($result) {
            return $result;
        }

        $result = $this->converter->convert($source, $target, $value);

        $this->cacheAdapter->set($key, $result);

        return $result;
    }
}