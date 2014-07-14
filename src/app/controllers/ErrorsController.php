<?php

namespace app\controllers;

/**
 * ErrorController Wyświetlanie strony błędu
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class ErrorsController extends \app\controllers\BaseController
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        parent::init();
    }

    /**
     * Pobranie informacji o błędzie i wywołanie strony z widokiem błędu
     *
     * @param String $error_msg
     */
    public function errorAction($error_msg = false)
    {
        if(!empty($_SESSION['error_msg']))
        {
            $error_msg = $_SESSION['error_msg'];
            unset($_SESSION['error_msg']);
        }
        else if(!$error_msg)
        {
            $error_msg = 'Unknown error';
        }

        $this->view->render(__METHOD__, $this->getMergedParams(array('error_msg' => $error_msg)), true);
    }
}
