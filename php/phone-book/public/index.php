<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Action\Auth\LoginAction;
use App\Action\Auth\LogoutAction;
use App\Action\Auth\RegisterAction;
use App\Action\Book\AddAction;
use App\Action\Book\DeleteAction;
use App\Action\Book\IndexAction;
use App\Action\Book\UpdateAction;
use App\Action\Image\UploadAction;
use App\Application;
use App\Web\Filter\OnlyAnonymousFilter;
use App\Web\Filter\OnlyLoggedFilter;

$app = new Application(require __DIR__ . '/../dependencies.php');

$app->route('/image/upload', UploadAction::class, OnlyLoggedFilter::class);
$app->route('/phone/update/{id}', UpdateAction::class, OnlyLoggedFilter::class);
$app->route('/phone/delete/{id}', DeleteAction::class, OnlyLoggedFilter::class);
$app->route('/phone/add', AddAction::class, OnlyLoggedFilter::class);
$app->route('/register', RegisterAction::class, OnlyAnonymousFilter::class);
$app->route('/login', LoginAction::class, OnlyAnonymousFilter::class);
$app->route('/logout', LogoutAction::class);
$app->route('/', IndexAction::class, OnlyLoggedFilter::class);

$app->run();