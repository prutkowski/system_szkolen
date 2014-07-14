<?php

namespace library;

/**
 * FrontController
 *
 * Singleton inicjalizujący rejestr, klasę Request oraz Router
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com
 *
 */
class FrontController
{
    /**
     * Singleton instance
     *
     * @var \library\FrontController
     */
    private static $instance = null;

    /**
     * Request
     *
     * @var \library\Request
     */
    private $request;

    public function __construct()
    {
        $this->initResources();
        $this->request = new \library\Request();
    }

    /**
     * Uruchomienie aplikacji
     * Włącza sesję, powołuje router
     */
    public function run()
    {
        Session::init();
        $router = new \library\Router();
        $router->route($this->request);
    }

    /**
     * Pobranie lub powołanie instancji (Singleton) klasy FrontController
     *
     * @return type
     */
    public static function getInstance()
    {
        if(!self::$instance instanceof self)
        {
            self::$instance = new \library\FrontController();
        }
        return self::$instance;
    }

    /**
     * Zwraca rządanie
     *
     * @return \library\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Odczytywanie pliku konfiguracyjnego i zapis danych w rejestrze
     *
     * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
     */
    public function initResources()
    {
        $config_array = parse_ini_file(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR .'application.ini', true);

        //Parsing nested ini file
        $config_array = $this->recursive_parse($this->parse_ini_advanced($config_array));

        $registry = \library\Registry::getInstance();

        foreach($config_array as $key => $val)
        {
            $registry->{$key} = $val;
        }
    }

    /**
     * Zagnieżdzone parsowanie plików konfiguracyjnych
     *
     * Metoda oparta na http://stackoverflow.com/questions/3242175/parsing-an-advanced-ini-file-with-php
     *
     * @author sphax3d
     * @author Paweł Rutkowski <rutkowski.pl@gmail.com> code formatting
     *
     * @param array $array
     * @return array
     */
    private function parse_ini_advanced($array)
    {
        $returnArray = array();

        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                $keys = explode(':', $key);
                if (!empty($keys[1]))
                {
                    $x = array();
                    foreach ($e as $tk => $tv)
                    {
                        $x[$tk] = trim($tv);
                    }
                    $x = array_reverse($x, true);

                    foreach ($x as $k => $v)
                    {
                        $c = $x[0];
                        if (empty($returnArray[$c]))
                        {
                            $returnArray[$c] = array();
                        }
                        if (isset($returnArray[$x[1]])) {
                            $returnArray[$c] = array_merge($returnArray[$c], $returnArray[$x[1]]);
                        }
                        if ($k === 0) {
                            $returnArray[$c] = array_merge($returnArray[$c], $array[$key]);
                        }
                    }
                }
                else
                {
                    $returnArray[$key] = $array[$key];
                }
            }
        }
        return $returnArray;
    }

    /**
     * Parsowanie rekurencyjne pliku konfiguracyjnego, oparte na http://stackoverflow.com/questions/3242175/parsing-an-advanced-ini-file-with-php
     *
     * @author sphax3d
     * @author Paweł Rutkowski <rutkowski.pl@gmail.com> code formatting
     *
     * @param array $array
     * @return array
     */
    private function recursive_parse($array)
    {
        $returnArray = array();

        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                if (is_array($value)) {
                    $array[$key] = recursive_parse($value);
                }

                $keys = explode('.', $key);

                if(!empty($keys[1]))
                {
                    $keys = array_reverse($keys, true);

                    if(isset($returnArray[$key]))
                    {
                        unset($returnArray[$key]);
                    }
                    if(!isset($returnArray[$keys[0]]))
                    {
                        $returnArray[$keys[0]] = array();
                    }

                    $first = true;

                    foreach ($keys as $k => $v)
                    {
                        if($first === true)
                        {
                            $next = $array[$key];
                            $first = false;
                        }
                        $next = array($v => $next);
                    }

                    $returnArray[$keys[0]] = array_merge_recursive($returnArray[$keys[0]], $next[$keys[0]]);
                }
                else
                {
                    $returnArray[$key] = $array[$key];
                }
            }
        }
        return $returnArray;
    }
}
