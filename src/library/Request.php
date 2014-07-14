<?php

namespace library;

/**
 * Request
 *
 * Klasa do obsługi żądań
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class Request {

    private $params = array();

    /**
     * Domyślny kontroler
     *
     * @var String
     */
    private $defaultControllerName = 'Index';

    /**
     * Domyślna akcja
     *
     * @var String
     */
    private $defaultActionName     = 'Index';

    /**
     * Kontroler
     *
     * @var String
     */
    private $controller = null;

    /**
     * Akcja
     *
     * @var String
     */
    private $action     = null;

    /**
     * Konstruktor żądania
     *
     * Pobiera parametry z $_SERVER, analizuje adres url i wyciąga z niego parametry
     * 
     */
    public function __construct()
    {
        $erequest_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
        $erequest_uri = rtrim(filter_var($erequest_uri, FILTER_SANITIZE_URL), '/');
        $exploded_uri = explode('/', $erequest_uri);
        $url_parts  = array_filter($exploded_uri);

        try
        {
            $this->controller = ($controller = array_shift($url_parts)) ? $controller : $this->defaultControllerName;
            $this->action = ($action = array_shift($url_parts)) ? $action : $this->defaultActionName;
            $this->params = $this->getParamsFromUrlParts($url_parts);
        }
        catch (\Exception $ex)
        {
            $this->error($ex);
        }
    }

    /**
     * Zwraca kontroler z żadania
     *
     * @return String
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Zwraca akcję z żądania
     *
     * @return String
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Zwraca tablicę parametrów
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Zwraca wartość parametru
     *
     * @param String $name
     * @return mixed (boolean|String)
     */
    public function getParam($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : false;
    }

    /**
     * Zwraca parametry
     *
     * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
     *
     * @param array $url_parts
     * @return array
     */
    private function getParamsFromUrlParts($url_parts)
    {
        $params = array();
        if(count($url_parts) > 0)
        {
            for($i = 0; $i < count($url_parts); $i += 2)
            {
                if(isset($url_parts[$i+1]))
                {
                    $params[$url_parts[$i]] = $url_parts[$i+1];
                }
            }
        }
        if(!empty($_POST))
        {
            $params = array_merge($params, $_POST);
        }
        if(!empty($_GET)) {
            $params = array_merge($params, $_GET);
        }
        unset($params['url']);

        return $params;
    }

    /**
     * Zwraca dane z $_POST'a
     *
     * @return array
     */
    public function getPost()
    {
        return !empty($_POST) ? $_POST : array();
    }
}