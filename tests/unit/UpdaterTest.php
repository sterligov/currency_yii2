<?php


namespace app\tests\unit;


use app\components\currency\FetcherInterface;
use app\components\currency\Updater;
use app\components\InvalidModelValueException;
use app\models\Currency;
use Codeception\Test\Unit;

class UpdaterTest extends Unit
{
    public function testUpdate_update()
    {
        $currency = new Currency();
        $currency->setAttributes([
            'code' => '840',
            'nominal' => '1',
            'rate' => '400.7',
            'name' => 'USA Dollar'
        ]);
        $rates[840] = $currency;

        $currency = new Currency();
        $currency->setAttributes([
            'code' => '978',
            'nominal' => '1',
            'rate' => '500.5',
            'name' => 'EURO'
        ]);
        $rates[978] = $currency;

        $fetcher = $this->fetcherMock($rates);
        $updater = new Updater($fetcher);
        $updater->update();

        $this->tester->seeRecord(Currency::class, $rates[978]->attributes);
        $this->tester->seeRecord(Currency::class, $rates[840]->attributes);


        $oldCurrency = Currency::findOne($rates[840]['id']);
        sleep(1);

        $newRate = 1;
        $oldCurrency['rate'] = $newRate;
        $rates[$oldCurrency['code']] = $oldCurrency;

        $fetcher = $this->fetcherMock($rates);
        $updater = new Updater($fetcher);
        $updater->update();
        $updatedCurrency = Currency::findOne($oldCurrency['id']);

        $this->assertEquals($updatedCurrency['rate'], $newRate);
        $this->assertNotEquals($updatedCurrency['updated_at'], $oldCurrency['updated_at']);
    }

    public function testUpdate_invalidModelValueException()
    {
        $currency = new Currency();
        $currency->setAttributes([
            'code' => '',
            'nominal' => 0,
            'rate' => '0',
            'name' => ''
        ]);
        $rates[840] = $currency;

        $fetcher = $this->fetcherMock($rates);
        $updater = new Updater($fetcher);

        $this->expectException(InvalidModelValueException::class);
        $updater->update();
    }

    private function fetcherMock(array $rates)
    {
        $fetcher = $this->getMockBuilder(FetcherInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $fetcher->expects($this->once())
            ->method('fetchCurrency')
            ->willReturn($rates);

        return $fetcher;
    }
}