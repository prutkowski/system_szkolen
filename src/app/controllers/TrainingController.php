<?php

namespace app\controllers;

/**
 * TrainingController Kontroler do zarządzania szkoleniami
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class TrainingController extends \app\controllers\BaseController
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
     * Lista szkoleń
     *
     * @param array $params
     */
    public function indexAction($params = array())
    {
        $this->layout_params['title'] = 'Szkolenia';

        $training = new \app\models\Training();
        $trainings = $training->getTrainings();
        $this->layout_params['trainings'] = $trainings;
        $this->layout_params['additional_scripts'] = array(
            'Training',
            'Training_Index'
        );
        $this->assignDataToJs('trainings', $trainings);
        $this->assignDataToJs('user_id', \library\Session::get('user_id'));
        $this->view->render(__METHOD__, $this->getMergedParams(array()));
    }

    public function addAction($params = array())
    {
        $post = $this->request->getPost();
        if(!empty($post))
        {
            try
            {
                $training = new \app\models\Training();
                $training->setName($post['training_name']);
                $training->setTotalVacancies($post['total_vacancies']);
                $training->save();

            }
            catch (\PDOException $ex)
            {
                $this->error($ex->getMessage());
            }
            $this->redirect('training', 'index');
        }
        $this->view->render(__METHOD__, $this->getMergedParams(array('title' => 'Dodaj szkolenie')));
    }

    public function getTrainingsAction($params = array())
    {
        $params = $this->request->getParams();

        $training = new \app\models\Training();

        $search_pattern = isset($params['search_pattern']) ? $params['search_pattern'] : "";
        $my_own = isset($params['my_own']) ? $params['my_own'] : false;
        $trainings = $training->getTrainings($search_pattern, $my_own);

        echo json_encode($trainings);
    }

    public function joinTrainingAction($params = array())
    {
        $params = $this->request->getParams();
        $user_training = new \app\models\UserTraining();
        $user_training->setTrainingId($params['training_id']);
        $user_training->setUserId($params['user_id']);
        $result = $user_training->save();

        echo json_encode(array($result));
    }

    public function leaveTrainingAction($params = array())
    {
        $params = $this->request->getParams();
        $user_training = new \app\models\UserTraining();
        $user_training->setTrainingId($params['training_id']);
        $user_training->setUserId($params['user_id']);
        $result = $user_training->delete($params['training_id'], $params['user_id']);

        echo json_encode(array($result));
    }

    public function trainingAction($params = array())
    {
        echo json_encode(array());
    }

    public function userListAction($params = array())
    {
        $params = $this->request->getParams();
        $training_id = $params['training_id'];
        $training_table = new \app\models\Training();
        $training = $training_table->getTraining($training_id);
        $user_training = new \app\models\UserTraining();
        $users = $user_training->getTrainingUsers($training_id);

        $this->view->render(__METHOD__, $this->getMergedParams(array('users' => $users, 'training_name' => $training['name'])));
    }
}
