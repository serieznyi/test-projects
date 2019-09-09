<?php
declare(strict_types=1);

use App\Event\Listener\LogRequestsListener;
use yii\web\Application;

$rules = [
    [
        'target' => Application::class,
        'event' => Application::EVENT_AFTER_REQUEST,
        'listener' => LogRequestsListener::class,
    ],
];

return ['components' => ['eventConfigurator' => ['rules' => $rules]]];
