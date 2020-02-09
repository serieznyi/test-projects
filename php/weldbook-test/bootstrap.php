<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

set_error_handler(static function($errno, $errorString, $errorFile, $errorLine ) {
    throw new ErrorException($errorString, $errno, 0, $errorFile, $errorLine);
});

spl_autoload_register(static function ($className) {
    $rootDir = __DIR__ . '/src/';
    $relativeClassPath = str_replace("\\", '/', $className);

    $path =  str_replace('App/', $rootDir, $relativeClassPath). '.php';

    require $path;
});

session_start();