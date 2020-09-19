<?php


namespace app\commands;


use app\components\currency\UpdaterInterface;
use yii\console\Controller;
use yii\helpers\Console;

class CurrencyController extends Controller
{
    public function actionUpdate()
    {
        try {
            $updater = \Yii::$container->get(UpdaterInterface::class);
            $updater->update();
        } catch (\Exception $e) {
            $error = sprintf('ERROR! %s', $e->getMessage());
            $this->stdout($error . PHP_EOL, Console::FG_RED);

            return;
        }

        $this->stdout('UPDATED' . PHP_EOL, Console::FG_GREEN);
    }
}