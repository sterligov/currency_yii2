<?php

namespace app\components\currency;

use app\models\Currency;

interface FetcherInterface
{
    /**
     * @throws \Throwable
     * @return array<string, Currency> where string key is currency code
     */
    public function fetchCurrency(): array;
}