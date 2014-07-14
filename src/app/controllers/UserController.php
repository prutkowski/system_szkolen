<?php

namespace app\controllers;

/**
 * UserController Kontroler obsługującuy logikę użytkowników systemu
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class UserController extends \app\controllers\BaseController
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
     * Logowanie użytkownika do systemu
     *
     * Obsługa formularza logowania do systemu
     *
     * @param array $params
     */
    public function loginAction($params = array())
    {
        $this->layout_params['title'] = 'Login';
        $this->view->render(__METHOD__, $this->getMergedParams(array('action_info' => 'login form')));

        //Form submited
        $post = $this->request->getPost();
        if(!empty($post))
        {
            try
            {
                $user = new \app\models\User();
                $user->authenticate($post);

                if($user->getId())
                {
                    \library\Session::set('user_login', $user->getLogin());
                    \library\Session::set('user_id', $user->getId());
                    \library\Session::set('is_admin', $user->isAdmin());
                }
            }
            catch (\PDOException $ex)
            {
                $this->error($ex->getMessage());
            }
            if(\library\Session::get('is_admin'))
            {
                $this->redirect('user', 'admin');
            }
            else
            {
                $this->redirect('dashboard', 'index');
            }
        }
    }

    /**
     * Wylogowanie użytkownika
     *
     * @param array $params
     */
    public function logoutAction($params = array())
    {
        \library\Session::destroy();
        $this->redirect('index', 'landingPage');
    }

    /**
     * Strona admina
     *
     * @param array $params
     */
    public function adminAction($params = array())
    {
        $this->view->render(__METHOD__, $this->getMergedParams(array('title' => 'Panel administratora')));
    }

    /**
     * Dodaj uzytkownika
     *
     * Obsługa formularza, który dodaje użytkownika
     *
     * @param array $params
     */
    public function addAction($params = array())
    {
        $this->view->render(__METHOD__, $this->getMergedParams(array('title' => 'Dodaj użytkownika')));

        //Form submited
        $post = $this->request->getPost();
        if(!empty($post))
        {
            try
            {
                $user = new \app\models\User();
                $user->setLogin($post['user_login']);
                $user->setPassword($post['user_password']);
                $user->setStatus(\app\models\User::STATUS_ACTIVE);
                $user->save($post);
            }
            catch (\PDOException $ex)
            {
                $this->error($ex->getMessage());
            }
        }
    }
}
