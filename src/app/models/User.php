<?php

namespace app\models;

/**
 * Model reprezentujący użytkownika systemu
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class User extends \library\Model
{
    protected $table_name = 'user';

    const STATUS_ADMIN = 10;

    const STATUS_ACTIVE = 1;

    const STATUS_NOT_READY = 0;

    const STATUS_DELETED = -1;

    /**
     * id użytkownika
     *
     * @var Int
     */
    private $id;

    /**
     * login użytkownika
     *
     * @var String
     */
    private $login;

    /**
     * hasło użytkownika
     *
     * @var String
     */
    private $password;

    /**
     * status użytkownika
     *
     * @var Int
     */
    private $status;

    /**
     * Ustawia id użytkownika
     *
     * @param Int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Pobiera id użytkownika
     *
     * @return Int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Ustawaia login użytkownika
     *
     * @param String $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * pobiera login
     *
     * @return String
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Ustawia hasło użytkownika,
     * korzystając z funckji sha1()
     *
     * TODO: solenie hasła
     *
     * @param String $password
     */
    public function setPassword($password)
    {
        $this->password = sha1($password);
    }

    /**
     * Pobiera hasło użytkownika
     *
     * @return String - hash z hasłem
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Ustawia status
     *
     * @param Int $status
     */
    public function setStatus($status)
    {
        $this->status = (int) $status;
    }

    /**
     * Pobiera status
     *
     * @return Int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sprawdza czy użytkownik jest adminem
     *
     * @return Boolean
     */
    public function isAdmin()
    {
        return ($this->status === self::STATUS_ADMIN) ? true : false;
    }

    /**
     * Zapis danych o użytkowniku
     *
     * @return Int|Boolean - Id zapisanego wiersza lub false przy niepowodzeniu
     *
     * @throws \PDOException
     */
    public function save()
    {
        if(empty($this->getLogin()) || empty($this->getPassword()))
        {
            throw new \PDOException('Wrong user data');
        }

        $statement = $this->db->prepare("INSERT INTO $this->table_name (login, password, status) VALUES (:login, :password, :status)");
        $statement->bindParam(':login', $this->getLogin(), \PDO::PARAM_INT);
        $statement->bindParam(':password', $this->getPassword(), \PDO::PARAM_STR);
        $statement->bindParam(':status', $this->getStatus(), \PDO::PARAM_INT);
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

    /**
     * Uwierzytelnianie użytkownika
     *
     * @param array $data
     *
     * @return boolean - uwierzytelniono?
     */
    public function authenticate($data)
    {
        $login    = $data['user_login'];
        $password = sha1($data['user_password']);

        $statement = $this->db->prepare("SELECT * FROM user WHERE login = :login AND password = :password");
        $statement->execute(array(':login' => $login, ':password' => $password));

        $user_data = $statement->fetch(\PDO::FETCH_ASSOC);
        if(!empty($user_data))
        {
            $this->setId($user_data['id']);
            $this->setLogin($user_data['login']);
            $this->setStatus($user_data['status']);

            return true;
        }
        else
        {
            return false;
        }
    }

    public function fetchUser($user_id)
    {
        $statement = $this->db->prepare("SELECT * FROM user WHERE id = :user_id");
        $statement->execute(array(':user_id' => $user_id));

        $user_data = $statement->fetch(\PDO::FETCH_ASSOC);
        if(!empty($user_data))
        {
            $this->setId($user_data['id']);
            $this->setLogin($user_data['login']);
            $this->setStatus($user_data['status']);

            return true;
        }
        else
        {
            return false;
        }
    }

    public function getTrenings()
    {
        $user_training_table = new \app\models\UserTraining();
        $trainings = $user_training_table->getUserTrainings($this->getId());
        return $trainings;
    }
}
