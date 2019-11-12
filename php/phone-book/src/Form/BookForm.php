<?php

namespace App\Form;

use App\Persistence\Database;
use App\Web\Util\AuthUtil;

/**
 * @package App\Form\Book
 */
class BookForm extends Form
{
    private static $MAX_PHONE_NUMBER_SIZE = 10;

    private static $SIZE_2MB = 2097152;

    private static $ALLOWED_EXTENSION = [
        'jpg',
        'png',
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $secondName;

    /**
     * @var int
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $photo;

    /**
     * @var Database
     */
    private $database;
    /**
     * @var string
     */
    private $imageTmpDir;

    /**
     * BookForm constructor.
     * @param Database $database
     * @param string $imageTmpDir
     */
    public function __construct(Database $database, $imageTmpDir)
    {
        $this->imageTmpDir = rtrim($imageTmpDir, DIRECTORY_SEPARATOR);
        $this->database = $database;

        $this->registerRules();
    }

    public function load($data)
    {
        $this->id = array_key_exists('id', $data) ? $data['id'] : $this->id;
        $this->firstName = array_key_exists('firstName', $data) ? $data['firstName'] : $this->firstName;
        $this->secondName = array_key_exists('secondName', $data) ? $data['secondName'] : $this->secondName;
        $this->phoneNumber = array_key_exists('phoneNumber', $data) ? $data['phoneNumber'] : $this->phoneNumber;
        $this->email = array_key_exists('email', $data) ? $data['email'] : $this->email;
        $this->photo = array_key_exists('photo', $data) ? $data['photo'] : $this->photo;
    }

    private function registerRules()
    {
        $this->addRule(function() {
            if (empty($this->firstName)) {
                $this->addError('firstName', 'First name is empty');
            }
        });

        $this->addRule(function() {
            if (empty($this->secondName)) {
                $this->addError('secondName', 'Second name is empty');
            }
        });

        $this->addRule(function() {
            if (empty($this->phoneNumber)) {
                $this->addError('phoneNumber', 'Phone number is empty');
            }
        });

        $this->addRule(function() {
            if (
                !$this->hasError('phoneNumber')
                && !preg_match('/^[\d]+$/', $this->phoneNumber)
            ) {
                $this->addError('phoneNumber', 'Phone number can contains only numbers');
            }
        });

        $this->addRule(function() {
            if (!$this->hasError('phoneNumber') && $this->isPhoneUsed()) {
                $this->addError('phoneNumber', 'Phone already used');
            }
        });

        $this->addRule(function() {
            if (
                !$this->hasError('phoneNumber')
                && strlen((string) $this->phoneNumber) > self::$MAX_PHONE_NUMBER_SIZE
            ) {
                $this->addError('phoneNumber', 'Max length of number is ' . self::$MAX_PHONE_NUMBER_SIZE);
            }
        });

        $this->addRule(function() {
            if (empty($this->email)) {
                $this->addError('email', 'Email is empty');
            }
        });

        $this->addRule(function() {
            if (
                !$this->hasError('email')
                && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->addError('email', 'Email is incorrect');
            }
        });

        $this->addRule(function() {
            if (!$this->hasError('email') && $this->isEmailUsed()) {
                $this->addError('email', 'Email already used');
            }
        });

        $this->addRule(function() {
            if (empty($this->photo)) {
                $this->addError('image', 'Choose image');
            }
        });

        $this->addRule(function() {
            if (!$this->hasError('image') && $this->isFileSizeEquals(self::$SIZE_2MB)) {
                $this->addError('image', 'Max size - ' . self::$SIZE_2MB / 1024 / 1024 . 'MB');
            }
        });

        $this->addRule(function() {

            if (!$this->hasError('image') && $this->isAllowedMimeType()) {
                $this->addError(
                    'image',
                    'Allowed types is ' . implode(',', self::$ALLOWED_EXTENSION)
                );
            }
        });
    }

    /**
     * @return bool
     */
    private function isPhoneUsed()
    {
        $row = $this->database->findBookRowByPhone($this->phoneNumber, AuthUtil::getUserId());

        if (!$row) {
            return false;
        }

        return !($this->id === $row['id']);
    }

    /**
     * @return bool
     */
    private function isEmailUsed()
    {
        $row = $this->database->findBookRowByEmail($this->email, AuthUtil::getUserId());

        if (!$row) {
            return false;
        }

        return !($this->id === $row['id']);
    }

    /**
     * @param int $sizeInBites
     * @return bool
     */
    private function isFileSizeEquals($sizeInBites)
    {
        $path = $this->imageToAbsolutePath();

        return filesize($path) > $sizeInBites;
    }

    /**
     * @return string
     */
    private function imageToAbsolutePath()
    {
        return $this->imageTmpDir . DIRECTORY_SEPARATOR . $this->photo;
    }

    private function isAllowedMimeType()
    {
        $image = $this->imageToAbsolutePath();

        return !in_array(pathinfo($image, PATHINFO_EXTENSION), self::$ALLOWED_EXTENSION, true);
    }
}