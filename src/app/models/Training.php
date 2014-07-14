<?php

namespace app\models;

/**
 * Model reprezentujący szkolenia na które użytkownicy mogą się zapisać
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class Training extends \library\Model
{
    protected $table_name = 'training';

    /**
     * id uszkolenia
     *
     * @var Int
     */
    private $id;

    /**
     * nazwa szkolenia
     *
     * @var String
     */
    private $name;

    /**
     * Ilość wolnych miejsc
     *
     * @var String
     */
    private $total_vacancies;


    /**
     * Ustawia id szkolenia
     *
     * @param Int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Pobiera id szkolenia
     *
     * @return Int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * Ustawaia nazwę szkolenia
     *
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * pobiera nazwę szkolenia
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Ustawia ilość wolnych miejsc
     *
     * @param String $vacancies
     */
    public function setTotalVacancies($total_vacancies)
    {
        $this->total_vacancies = (int) $total_vacancies;
    }

    /**
     * Pobiera ilość wolnych miejsc
     *
     * @return Int
     */
    public function getTotalVacancies()
    {
        return (int) $this->total_vacancies;
    }

    /**
     * Pobiera szkolenia
     *
     * @param $search_term - wzorzec po którym można wyszukiwać szkolenia
     * @return array
     */
    public function getTrainings($search_term = '')
    {
        $statement = $this->db->prepare("SELECT * FROM training LEFT JOIN user_training ON (training.id = user_training.training_id) WHERE name LIKE :searchTerm ORDER BY id");
        $statement->execute(array(':searchTerm' => '%'.$search_term.'%'));

        //$user_training = new \app\models\UserTraining();

        $trainings = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $result = array();

        foreach($trainings as $training)
        {
            $user_assigned = $training['user_id'] ? 1 : 0;
            $result[$training['id']]['id'] = $training['id'];
            $result[$training['id']]['name'] = $training['name'];
            $result[$training['id']]['total_vacancies'] = $training['total_vacancies'];
            $result[$training['id']]['user_cnt'] = @$result[$training['id']]['user_cnt'] + $user_assigned;
            $result[$training['id']]['free_vacancies'] = $result[$training['id']]['total_vacancies'] - $result[$training['id']]['user_cnt'];
        }

        $trainings = array();
        foreach($result as $training)
        {
            $trainings[] = $training;
        }
        return $trainings;
    }

    /**
     * Pobiera szkolenie
     *
     * @param int $training_id
     * @return \app\model\Training
     */
    public function getTraining($training_id)
    {
        $statement = $this->db->prepare("SELECT * FROM training WHERE id LIKE :training_id");
        $statement->execute(array(':training_id' => '%'.$training_id.'%'));
        return $statement->fetch();
    }

    /**
     * Zapis szkolenia
     *
     * @return boolean
     * @throws \PDOException
     */
    public function save()
    {
        if(empty($this->getName()) || empty($this->getTotalVacancies()))
        {
            throw new \PDOException('Wrong data');
        }

        $statement = $this->db->prepare("INSERT INTO $this->table_name (name, total_vacancies) VALUES (:name, :total_vacancies)");
        $statement->bindParam(':name', $this->getName(), \PDO::PARAM_STR);
        $statement->bindParam(':total_vacancies', $this->getTotalVacancies(), \PDO::PARAM_INT);
        $result = $statement->execute();

        if($result)
        {
            return $this->db->lastInsertId();
        }
        else
        {
            return false;
        }
    }
}
