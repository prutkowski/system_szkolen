<?php

namespace app\controllers;

/**
 * BaseController - Kontroler bazowy po którym dziedziczą wszystkie kontrolery systemu
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class BaseController
{
    /**
     * Rejestr
     *
     * @var array
     */
    protected $storage = null;

    /**
     * Instancja klasy View
     *
     * @var \library\View
     */
    protected $view = null;

    /**
     * Parametry przekazywane do widoku
     *
     * @var array
     */
    protected $layout_params = array();

    /**
     * Instancja klasy FrontController
     *
     * @var \library\FrontController
     */
    protected $frontController;

    /**
     * Instancja klasy Request
     *
     * @var \library\Request
     */
    protected $request;

    /**
     * Metoda inicjująca kontroler bazowy
     *
     * Inicjalizacja:
     *  -widoku
     *  -rejestru
     *
     * Przekazanie domyślnych parametrów do layout'u
     */
    public function init() {
        $instance = \library\Registry::getInstance();
        $this->storage = $instance->getStorage();
        $this->view = new \library\View();

        $this->request = \library\FrontController::getInstance()->getRequest();

        //dane które zostaną przekazane do javascript'u
        $this->layout_params['js_data'] = array();
        $this->layout_params['additional_scripts'] = array();

        $this->layout_params['title'] = "";
        $this->layout_params['action_info'] = "";
        $this->layout_params['footer_info'] = "&copy Paweł Rutkowski";
        $this->layout_params['base_url'] = $this->storage['resources']['base_url'];
    }

    /**
     * Mergowanie parametrów
     *
     * @param type $params
     * @return type
     */
    protected function getMergedParams($params = array())
    {
        return array_merge($this->layout_params, $params);
    }

    /**
     * Przekierowanie na stronę obsługującą wyświetlanie błędów
     *
     * Przekazuje informację o błędzie
     *
     * @param String $error_msg
     */
    protected function error($error_msg)
    {
        $_SESSION['error_msg'] = $error_msg;
        $this->redirect('errors', 'error');
    }

    /**
     * Pobiera dane z rejestru
     *
     * @return array
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Metoda obsługująca przekierowanie na dany kontroler/akcję
     *
     * @param String $controller
     * @param String $method default 'index'
     * @param array $args
     */
    public function redirect($controller, $action = "index", $args = array())
    {
        $base_url = $this->storage['resources']['base_url'];

        $location = $base_url . "/" . $controller . "/" . $action . "/" . implode("/",$args);

        header("Location: " . $location);
        exit;
    }

    /**
     * Przekazuje dane do
     *
     * @param String $key
     * @param mixed $data
     */
    public function assignDataToJs($key, $data)
    {
        $this->layout_params['js_data'][$key] = $data;
    }
}
