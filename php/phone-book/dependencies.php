<?php

use App\Action\Auth\LoginAction;
use App\Action\Auth\LogoutAction;
use App\Action\Auth\RegisterAction;
use App\Action\Book\AddAction;
use App\Action\Book\DeleteAction;
use App\Action\Book\IndexAction;
use App\Action\Book\UpdateAction;
use App\Action\Image\UploadAction;
use App\Application;
use App\Form\BookForm;
use App\Persistence\Database;
use App\Service\AuthService;
use App\Service\PhoneImageService;
use App\Service\PhoneService;
use App\View;
use App\Web\Filter\OnlyAnonymousFilter;
use App\Web\Filter\OnlyLoggedFilter;

$tmpImageDir = __DIR__ . '/public/tmp/';

return [
    OnlyLoggedFilter::class => static function (Application $application) {
        return new OnlyLoggedFilter($application->buildObject(AuthService::class));
    },
    OnlyAnonymousFilter::class => static function (Application $application) {
        return new OnlyAnonymousFilter($application->buildObject(AuthService::class));
    },
    View::class => static function () {
        $userData = App\Web\Util\AuthUtil::getUserData();

        return new View(__DIR__ . '/view', 'layout/default', [
            'language' => 'ru',
            'charset' => 'utf-8',
            'username' => array_key_exists('username', $userData) ? $userData['username'] : '',
        ]);
    },
    AuthService::class => static function (Application $application) {
        return new AuthService(
            $application->buildObject(Database::class)
        );
    },
    Database::class => static function () {
        return Database::instance('mysql:dbname=test;host=mysql;charset=UTF8;', 'test', 'test');
    },
    RegisterAction::class => static function (Application $application) {
        return new RegisterAction(
            $application->buildObject(View::class),
            $application->buildObject(Database::class),
            $application->buildObject(AuthService::class)
        );
    },
    LoginAction::class => static function (Application $application) {
        return new LoginAction(
            $application->buildObject(View::class),
            $application->buildObject(AuthService::class)
        );
    },
    PhoneService::class => static function (Application $application) {
        return new PhoneService(
            $application->buildObject(Database::class),
            $application->buildObject(AuthService::class),
            $application->buildObject(PhoneImageService::class)
        );
    },
    BookForm::class => static function (Application $application) {
        return new BookForm(
            $application->buildObject(Database::class),
            __DIR__ . '/public/tmp/'
        );
    },
    AddAction::class => static function (Application $application) use ($tmpImageDir) {
        return new AddAction(
            $application->buildObject(View::class),
            $application->buildObject(PhoneService::class),
            $application->buildObject(BookForm::class),
            '/tmp/'
        );
    },
    LogoutAction::class => static function (Application $application) {
        return new LogoutAction(
            $application->buildObject(AuthService::class)
        );
    },
    UploadAction::class => static function (Application $application) {
        return new UploadAction(
            $application->buildObject(PhoneImageService::class)
        );
    },
    IndexAction::class => static function (Application $application) {
        return new IndexAction(
            $application->buildObject(View::class),
            $application->buildObject(PhoneService::class),
            '/images/'
        );
    },
    DeleteAction::class => static function (Application $application) {
        return new DeleteAction(
            $application->buildObject(PhoneService::class)
        );
    },
    UpdateAction::class => static function (Application $application) {
        return new UpdateAction(
            $application->buildObject(View::class),
            $application->buildObject(Database::class),
            $application->buildObject(PhoneService::class),
            $application->buildObject(BookForm::class),
            '/images/'
        );
    },
    PhoneImageService::class => static function (Application $application) use ($tmpImageDir) {
        return new PhoneImageService(
            $tmpImageDir,
            __DIR__ . '/public/images/',
            __DIR__ . '/public/',
            $application->buildObject(AuthService::class)
        );
    },
];