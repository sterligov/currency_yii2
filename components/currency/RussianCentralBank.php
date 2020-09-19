<?php


namespace app\components\currency;


use app\models\Currency;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RussianCentralBank implements FetcherInterface
{
    private const URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function fetchCurrency(): array
    {
        $response = $this->client->get(self::URL);
        $content = $response->getBody()->getContents();

        $currencies = [];
        $rates = new \SimpleXMLElement($content);

        foreach ($rates as $rate) {
            $value = str_replace(',', '.', (string)$rate->Value);
            $code = (string)$rate->NumCode;

            $currency = new Currency();
            $currency->setAttributes([
                'code' => $code,
                'rate' => $value,
                'name' => (string)$rate->Name,
                'nominal' => (int)$rate->Nominal
            ]);

            $currencies[$code] = $currency;
        }

        return $currencies;
    }
}