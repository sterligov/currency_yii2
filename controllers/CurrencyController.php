<?php declare(strict_types=1);


namespace app\controllers;


use app\models\Currency;
use yii\data\ActiveDataProvider;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CurrencyController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionCollection()
    {
        $currencies = Currency::find();

        return new ActiveDataProvider([
            'query' => $currencies,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    public function actionView(int $id)
    {
        $currency = Currency::findOne($id);
        if (!$currency) {
            throw new NotFoundHttpException();
        }

        return $currency;
    }
}