<?php

namespace library;

/**
 * Router aplikacji, pobiera dane z Requestu i wywołuje odpowiednią akcję z parametami
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class Router {

    /**
     * Gdzie znajdują się kontrollery
     *
     * @var String
     */
    private $controllersPath = null;

    /**
     * Lista dozwolonych kontrolerów
     *
     * TODO: Wynieść poza katalog library
     *
     * @var array
     */
    private $controllerWhiteList = array(
        'IndexController',
        'UserController',
        'DashboardController',
        'TrainingController',
        'ErrorsController'
    );

    private $notLoggedUserAllowed = array(
        'IndexController::landingPageAction',
        'UserController::loginAction',

    );

    public function __construct()
    {
        $this->controllersPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
    }

    /**
     * Obsługuje żądanie i wywołuje odpowiednią akcje kontrolera
     *
     * @author Paweł Rutkowski <rutkowski.pl@gmial.com>
     * @param \library\Request $request
     * @throws \Exception
     */
    public function route(Request $request)
    {
        try
        {
            $controller = $request->getController() . 'Controller';
            $action = $request->getAction() . 'Action';
            $params = $request->getParams();

            //Przekierowanie na strone logowania jeżeli próbujemy się dostać w inne miejsce
            if(!Session::get('user_login'))
            {
                if(!in_array(ucfirst($controller) . '::' . $action, $this->notLoggedUserAllowed))
                {
                    $this->callAction('UserController', 'LoginAction', $params);
                    return;
                }
            }

            $controllerFile = $this->controllersPath . $controller . '.php';
            if(!file_exists($controllerFile))
            {
                throw new \Exception("404 - Controller $controller not found");
            }

            if(!in_array(ucfirst($controller), $this->controllerWhiteList))
            {
                throw new \Exception ("Can't reach file $controller");
            }

            $this->callAction(ucfirst($controller), ucfirst($action), $params);

        } catch (\Exception $ex)
        {
            $this->error($ex);
        }
    }

    /**
     * Wywołanie akcji
     *
     * @param String $controller
     * @param String $action
     * @param array $params
     * @throws \Exception
     */
    private function callAction($controller, $action, $params)
    {
        $controller_with_namespace = "\app\controllers\\" . $controller;
        $controller = new $controller_with_namespace();

        if(!is_callable(array($controller, $action)))
        {
            throw new \Exception("404 - Method $action not found");
        }

        call_user_func_array(array($controller, $action), $params);
    }

    /**
     * Wyświetla stronę błędu
     *
     * @param Exception $ex
     */
    private function error($ex)
    {
        $errorController = new \app\controllers\ErrorsController();
        call_user_func(array($errorController, 'errorAction'), $ex->getMessage());
    }

}