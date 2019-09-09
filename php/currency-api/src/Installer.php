<?php

namespace App;

use Assert\Assertion;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;
use yii\base\Security;

/**
 * Class Installer
 * @package App
 */
final class Installer
{
    private const ROOT_DIR = '/var/www';

    private const RULES_FILE_DIR = '/var/www/etc/init_rules.yml';

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function createRuntimeDirs()
    {
        $rules = self::getRules();

        $filesystem = new Filesystem(new Local(self::ROOT_DIR));

        foreach ($rules['create_dirs'] as $file) {
            if (is_dir($file)) {
                echo "rm -R $file\n";
                $filesystem->deleteDir($file);
            }

            echo "mkdir $file\n";
            $filesystem->createDir($file);
        }
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function makeExecutable()
    {
        $rules = self::getRules();
        $root = self::ROOT_DIR;

        foreach ($rules['set_executable'] as $executable) {
            $filePath = "$root/$executable";

            echo "chmod 0755 $executable\n";

            if (is_executable($filePath) === false && chmod($filePath, 0755) === false) {
                $error = error_get_last();
                echo $error['message'];
                exit(1);
            }
        }
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function makeWritable()
    {
        $rules = self::getRules();
        $root = self::ROOT_DIR;

        foreach ($rules['set_writable'] as $writable) {
            $filePath = "$root/$writable";

            if (file_exists($filePath) === false) {
                $error = error_get_last();
                echo $error['message'];
                exit(1);
            }

            echo "chmod 0777 $writable\n";
            if (chmod($filePath, 0777) === false) {
                $error = error_get_last();
                echo $error['message'];
                exit(1);
            }
        }
    }

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \yii\base\Exception
     */
    public static function generateCookieValidationKey()
    {
        $envFile = self::ROOT_DIR . '/.env';

        Assertion::file($envFile, sprintf('Файл %s окружения не существует', $envFile));

        $dotEnv = new Dotenv();
        $env = $dotEnv->parse(file_get_contents($envFile), $envFile);

        $env['COOKIE_VALIDATION_KEY'] = (new Security())->generateRandomString();

        ksort($env);

        $file = fopen($envFile, 'w');

        echo "Обновляем .env файл\n";

        foreach ($env as $key => $value) {
            fputs($file, "$key=$value\n");
        }

        fclose($file);
    }

    /**
     * @return array
     * @throws \Assert\AssertionFailedException
     */
    private static function getRules(): array
    {
        Assertion::file(self::RULES_FILE_DIR, sprintf('Файл %s не существует', self::RULES_FILE_DIR));

        return Yaml::parse(file_get_contents(realpath(self::RULES_FILE_DIR)));
    }
}