<?php


namespace app\components\currency;


use app\components\InvalidModelValueException;
use app\models\Currency;

class Updater implements UpdaterInterface
{
    private FetcherInterface $fetcher;

    public function __construct(FetcherInterface $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    /**
     * @throws \Throwable
     */
    public function update()
    {
        $freshCurrencies = $this->fetcher->fetchCurrency();
        $currencies = Currency::find()->all();

        foreach ($currencies as $currency) {
            $code = $currency['code'];
            if (empty($freshCurrencies[$code])) {
                continue;
            }

            $currency->setAttributes($freshCurrencies[$code]->attributes);
            $saved = $currency->save();
            if (!$saved) {
                $this->throwError($currency);
            }

            unset($freshCurrencies[$code]);
        }

        // save new currencies
        foreach ($freshCurrencies as $currency) {
            $saved = $currency->save();
            if (!$saved) {
                $this->throwError($currency);
            }
        }
    }

    /**
     * @throws InvalidModelValueException
     */
    private function throwError(Currency $currency)
    {
        $errors = $currency->getErrorSummary(true);
        $errors = implode(PHP_EOL, $errors);
        $error = sprintf("ERROR!\n %s", $errors);

        throw new InvalidModelValueException($error);
    }
}