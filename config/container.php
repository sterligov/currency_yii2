<?php

return [
    'definitions' => [
        \app\components\currency\FetcherInterface::class => ['class' => \app\components\currency\RussianCentralBank::class],
        \app\components\currency\UpdaterInterface::class => ['class' => \app\components\currency\Updater::class]
    ]
];