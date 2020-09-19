<?php

namespace app\tests\api;


use Codeception\Util\HttpCode;

class CurrencyCest
{
    public function getModel(\ApiTester $I)
    {
        $I->sendGET('/currency/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'code' => 'string',
            'rate' => 'string',
            'nominal' => 'integer',
            'updated_at' => 'string',
        ]);
    }

    public function getCollection(\ApiTester $I)
    {
        $I->sendGET('/currencies');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'code' => 'string',
            'rate' => 'string',
            'nominal' => 'integer',
            'updated_at' => 'string',
        ]);
    }
}
