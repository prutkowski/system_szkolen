<?php

namespace library;

/**
 * Nadpisana klasa PDO
 * Połączenie z bazą inicjalizowane danymi pobranymi z configa
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 * @extends \PDO
 */
class Database extends \PDO
{
    public function __construct()
    {
        $instance = \library\Registry::getInstance();
        $storage = $instance->getStorage();
        $db_settings = $storage['resources']['db'];

        parent::__construct($db_settings['destination'], $db_settings['user'], $db_settings['password']);
    }
}
