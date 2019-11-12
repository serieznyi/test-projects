<?php

namespace App\Web\Util;

class AuthUtil
{
    /**
     * @return bool
     */
    public static function isAuthorized() {
        return array_key_exists('user', $_SESSION) && $_SESSION['user'];
    }

    /**
     * @return array
     */
    public static function getUserData()
    {
        return array_key_exists('user', $_SESSION) ? $_SESSION['user'] : [];
    }

    public static function getUserId()
    {
        $data = self::getUserData();

        return $data['id'];
    }
}