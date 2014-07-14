<?php

namespace app\controllers;

/**
 * IndexController
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class IndexController extends BaseController
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
     * Akcja index
     *
     * @param String $params
     */
    public function indexAction($params = array())
    {
        if(!\library\Session::get('user_login'))
        {
            $this->redirect('user', 'login');
        }
        $this->view->render(__METHOD__, $this->getMergedParams(array()));
    }

    /**
     * Strona początkowa
     *
     * @param String $params
     */
    public function landingPageAction($params = array())
    {
        $this->view->render(__METHOD__, $this->getMergedParams(array('title' => 'Strona startowa')));
    }
}
