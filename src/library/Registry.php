<?php

namespace library;

/**
 * Singleton class Registry
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class Registry
{
    /**
     * Instancja singleton
     *
     * @var \library\Registry
     */
    private static $instance = null;

    /**
     * Tablica z danymi rejestru
     *
     * @var array
     */
    private $storage = array();

    /**
     * Pobieranie lub inicjalizacja klasy
     *
     * @return library\Registry
     */
    public static function getInstance()
    {
        if(!self::$instance instanceof self)
        {
            self::$instance = new \library\Registry();
        }
        return self::$instance;
    }

    /**
     * Magiczna metoda do ustawiania danych w rejestrze
     *
     * @param String $key
     * @param mixed $val
     */
    public function __set($key, $val)
    {
        $this->storage[$key] = $val;
    }

    /**
     * Zwraca wartość zapisaną w rejestrze
     *
     * @param String $key
     * @return mixed - wartość zapisana w rejestrze lub false, jeżeli nie ma jej w rejestrze
     */
    public function __get($key)
    {
        if(isset($this->storage[$key]))
        {
            return $this->storage[$key];
        }
        else
        {
            return false;
        }
    }

    /**
     * Zwraca tablicę z danymi z rejestru
     *
     * @return array
     */
    public function getStorage()
    {
        return $this->storage;
    }

}
