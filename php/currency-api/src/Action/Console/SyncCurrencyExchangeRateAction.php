<?php declare(strict_types=1);

namespace App\Action\Console;

use App\Service\CurrencySyncService;
use App\Service\RemoteCurrencyRateLoader\RemoteCurrencyRateLoader;
use function Logging\logDebug;
use yii\base\Action;

/**
 * @package App\Action\Console
 */
class SyncCurrencyExchangeRateAction extends Action
{
    /**
     * @var RemoteCurrencyRateLoader
     */
    private $currencyRateLoader;
    /**
     * @var CurrencySyncService
     */
    private $currencySyncService;

    public function __construct(
        $id,
        $controller,
        RemoteCurrencyRateLoader $currencyRateLoader,
        CurrencySyncService $currencySyncService,
        $config = []
    )
    {
        $this->currencyRateLoader = $currencyRateLoader;
        $this->currencySyncService = $currencySyncService;

        parent::__construct($id, $controller, $config);
    }

    public function run(): void
    {
        foreach ($this->currencyRateLoader->load() as $currencyRate) {
            logDebug(
                "Сохраняю {$currencyRate->getSourceCurrency()} -> {$currencyRate->getTargetCurrency()}",
                __METHOD__
            );

            $this->currencySyncService->syncCurrency($currencyRate);
        }
    }
}