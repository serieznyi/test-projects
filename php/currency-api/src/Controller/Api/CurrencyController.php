<?php declare(strict_types=1);


namespace App\Controller\Api;


use App\Action\Api\ConvertIndex;
use yii\web\Controller;

/**
 * @package App\Controller\Api
 */
class CurrencyController extends Controller
{
    public function actions(): array
    {
        return [
            'convert' => ConvertIndex::class,
        ];
    }
}