<?php
declare(strict_types=1);

namespace App\Controller\Console;

use App\Action\Console\SyncCurrencyExchangeRateAction;

/**
 * @package App\Controller\Console
 */
final class Controller extends \yii\console\Controller
{
    public function actions(): array
    {
        return [
            'sync-currency-exchange-rate' => SyncCurrencyExchangeRateAction::class,
        ];
    }
}
