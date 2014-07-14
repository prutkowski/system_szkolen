<?php

namespace app\controllers;

/**
 * DashboardController
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class DashboardController extends BaseController
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
     * Zwraca informację na dashboard użytkownika
     *
     * @param array $params
     */
    public function indexAction($params = array())
    {
        $user = new \app\models\User();
        $user->fetchUser(\library\Session::get('user_id'));
        $trainings = $user->getTrenings();

        $this->view->render(__METHOD__, $this->getMergedParams(array('title' => 'Moje szkolenia', 'trainings' => $trainings)));
    }
}
