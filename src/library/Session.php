<?php

namespace library;

/**
 * Klasa sesji
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class Session
{
    /**
     * Czy sesja aplikacji została już powołana
     *
     * @var type
     */
    public static $session_started = false;

    /**
     * Uruchmienie sesji
     */
    public static function init()
    {
        if(!self::$session_started)
        {
            self::$session_started = true;
            session_start();
        }
    }

    /**
     * Ustawienie danych w sesji
     *
     * @param String $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Pobranie danych z sesji
     *
     * @param String $key
     * @return mixed - wartość z sesji lub null jeżeli w sesji nie było żadnej danej pod tym kluczem
     */
    public static function get($key)
    {
        if(isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        else
        {
            return null;
        }
    }

    /**
     * Znieszczenie sesji
     */
    public static function destroy()
    {
        session_destroy();
    }
}
