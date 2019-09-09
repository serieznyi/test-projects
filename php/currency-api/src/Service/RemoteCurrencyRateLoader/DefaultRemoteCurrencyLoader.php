<?php declare(strict_types=1);


namespace App\Service\RemoteCurrencyRateLoader;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Throwable;
use function Logging\logException;

class DefaultRemoteCurrencyLoader implements RemoteCurrencyRateLoader
{
    private const BASE_CURRENCY = 'USD';

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $list = [];

        foreach ($this->getArrayFromSource() as $targetCurrency => $coefficient) {
            $list[] = new CurrencyRate(
                self::BASE_CURRENCY,
                $targetCurrency,
                $coefficient
            );
        }

        return $list;
    }

    private function getArrayFromSource(): array
    {
        try {
            $client = new Client;
            $response = $client->request(
                'GET',
                'https://api.exchangerate-api.com/v4/latest/' . self::BASE_CURRENCY,
                [
                    'connect_timeout' => 1,
                    'read_timeout' => 1,
                    'verify' => false,
                ]
            );

            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException('Неверный код ответа', $response);
            }

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['rates'];
        } catch (Throwable $exception) {
            logException($exception);
        }

        return [];
    }
}