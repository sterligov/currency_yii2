<?php


namespace app\tests\unit;


use app\components\currency\RussianCentralBank;
use app\models\Currency;
use Codeception\Test\Unit;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;

class RussianCentralBankTest extends Unit
{
    protected function _after()
    {
        \Yii::$app
            ->db
            ->createCommand()
            ->truncateTable('currency')
            ->execute();
    }

    public function testFetchCurrency_ok()
    {
        $response = $this->getMockBuilder(ResponseInterface::class)
            ->addMethods(['getContents'])
            ->getMockForAbstractClass();
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->returnSelf());
        $response->expects($this->once())
            ->method('getContents')
            ->willReturn($this->validXML);

        $client = $this->getMockBuilder(Client::class)
            ->onlyMethods(['get'])
            ->addMethods(['getBody', 'getContents'])
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->willReturn($response);

        $bank = new RussianCentralBank($client);
        $actual = $bank->fetchCurrency();


        $currency = new Currency();
        $currency->setAttributes([
            'code' => '840',
            'nominal' => '1',
            'rate' => '400.7',
            'name' => 'USA Dollar'
        ]);
        $expected[840] = $currency;

        $currency = new Currency();
        $currency->setAttributes([
            'code' => '978',
            'nominal' => '1',
            'rate' => '500.5',
            'name' => 'EURO'
        ]);
        $expected[978] = $currency;

        $this->assertEquals($expected, $actual);
    }

    public function testFetchCurrency_badConnectionException()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->willThrowException(new TransferException());

        $bank = new RussianCentralBank($client);

        $this->expectException(GuzzleException::class);
        $bank->fetchCurrency();
    }

    public function testFetchCurrency_invalidXML()
    {
        $response = $this->getMockBuilder(ResponseInterface::class)
            ->addMethods(['getContents'])
            ->getMockForAbstractClass();
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->returnSelf());
        $response->expects($this->once())
            ->method('getContents')
            ->willReturn('invalid xml');

        $client = $this->getMockBuilder(Client::class)
            ->onlyMethods(['get'])
            ->addMethods(['getBody', 'getContents'])
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->willReturn($response);

        $bank = new RussianCentralBank($client);

        $this->expectException(\Exception::class);
        $bank->fetchCurrency();
    }

    private string $validXML = <<<'EOD'
<?xml version="1.0" encoding="windows-1251"?>
<ValCurs Date="19.09.2020" name="Foreign Currency Market">
    <Valute ID="R01010">
        <NumCode>840</NumCode>
        <CharCode>USD</CharCode>
        <Nominal>1</Nominal>
        <Name>USA Dollar</Name>
        <Value>400.7</Value>
    </Valute>
    <Valute ID="R01020A">
        <NumCode>978</NumCode>
        <CharCode>EUR</CharCode>
        <Nominal>1</Nominal>
        <Name>EURO</Name>
        <Value>500.5</Value>
    </Valute>
</ValCurs>
EOD;
}