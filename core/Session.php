<?php
namespace application\core;

class Session
{
    public static function data(string $key = '', $value = '')
    {
        return !empty($value) ? Session::set($key, $value) : Session::get($key);
    }

    public static function set(string $key = '', $value = '')
    {
        $sessionKey = Session::getSessionkey();

        if (!empty($value))
        {
            if (!empty($key))
            {
                $_SESSION[$sessionKey][$key] = $value;
                return true;
            }
        }

        return false;
    }

    public static function isset(string $key = '')
    {
        return Session::data($key) !== null;
    }

    public static function empty(string $key = '')
    {
        return  empty(Session::data($key));
    }

    public static function get(string $key = '')
    {
        $sessionKey = Session::getSessionkey();

        if (!empty($key))
        {
            if (isset($_SESSION[$sessionKey][$key]))
            {
                return $_SESSION[$sessionKey][$key];
            }
            else
            {
                return false;
            }
        }

        if (isset($_SESSION[$sessionKey]))
        {
            return $_SESSION[$sessionKey];
        }
    }

    public static function remove(string $key = '')
    {
        $sessionKey = Session::getSessionkey();

        if (!empty($key))
        {
            if (isset($_SESSION[$sessionKey][$key]))
            {
                unset($_SESSION[$sessionKey][$key]);
                return true;
            }

            return false;
        }
        else
        {
            unset($_SESSION[$sessionKey]);
            return true;
        }
    }

    public static function flash(string $key = '', $value = '')
    {
        $flashData = self::data($key, $value);

        if (empty($value))
        {
            self::remove($key);
        }

        return $flashData;
    }

    public static function getSessionkey()
    {
        global $configurations;

        if (!empty($configurations['session']))
        {
            if (!empty($configurations['session']['sessionKey']))
            {
                return $configurations['session']['sessionKey'];
            }
            else
            {
                echo "Vui long cau hinh sessionKey"."</br>";
            }
        }
        else
        {
            echo "Vui long cau hinh session"."</br>";
        }

    }

}

