<?php

namespace app\models;

/**
 * Model reprezentujący zapisanych użytkowników na szkolenia
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class UserTraining extends \library\Model
{
    protected $table_name = 'user_training';

    /**
     * id użytkownika
     *
     * @var Int
     */
    private $user_id;

    /**
     * id szkolenia
     *
     * @var Int
     */
    private $training_id;


    /**
     * Ustawia id użytkownika
     *
     * @param Int $id
     */
    public function setUserId($user_id)
    {
        $this->user_id = (int) $user_id;
    }

    /**
     * Pobiera id użytkownika
     *
     * @return Int
     */
    public function getUserId()
    {
        return (int) $this->user_id;
    }

    /**
     * Ustawia id szkolenia
     *
     * @return Int
     */
    public function setTrainingId($training_id)
    {
        $this->training_id = $training_id;
    }

    /**
     * Pobiera id szkolenia
     *
     * @return Int
     */
    public function getTrainingId()
    {
        return (int) $this->training_id;
    }


    /**
     * Pobiera szkolenia danego użytkownika
     *
     * @param $search_term - wzorzec po którym można wyszukiwać szkolenia
     * @return array
     */
    public function getUserTrainings($user_id)
    {
        $statement = $this->db->prepare("SELECT training_id, name FROM user_training LEFT JOIN training ON (user_training.training_id = training.id) WHERE user_id = :user_id");
        $statement->execute(array(':user_id' => $user_id));

        $trainings = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $trainings;
    }

    /**
     * Pobiera użytkowników danego szkolenia
     *
     * @param $search_term - wzorzec po którym można wyszukiwać szkolenia
     * @return array
     */
    public function getTrainingUsers($training_id)
    {
        $statement = $this->db->prepare("SELECT user_id, login FROM user_training LEFT JOIN user ON (user_training.user_id = user.id) WHERE training_id = :training_id");
        $statement->execute(array(':training_id' => $training_id));

        $users = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $users;
    }

    /**
     * Zapisuje informacje o tym że użytkownik należy do danego szkolenia
     *
     * @return boolean
     * @throws \PDOException
     */
    public function save()
    {
        if(empty($this->getUserId()) || empty($this->getTrainingId()))
        {
            throw new \PDOException('Wrong data');
        }

        $statement = $this->db->prepare("INSERT INTO $this->table_name (user_id, training_id) VALUES (:user_id, :training_id)");
        $statement->bindParam(':user_id', $this->getUserId(), \PDO::PARAM_INT);
        $statement->bindParam(':training_id', $this->getTrainingId(), \PDO::PARAM_INT);
        $result = $statement->execute();

        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Usuwa wpis o użytkowniku w danym szkoleniu
     *
     * @return boolean
     * @throws \PDOException
     */
    public function delete()
    {
        if(empty($this->getUserId()) || empty($this->getTrainingId()))
        {
            throw new \PDOException('Wrong data');
        }

        $statement = $this->db->prepare("DELETE FROM $this->table_name WHERE user_id = :user_id AND training_id = :training_id");
        $statement->bindParam(':user_id', $this->getUserId(), \PDO::PARAM_INT);
        $statement->bindParam(':training_id', $this->getTrainingId(), \PDO::PARAM_INT);
        $result = $statement->execute();

        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
