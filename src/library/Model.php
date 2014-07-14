<?php

namespace library;

/**
 * Klasa po której dziedziczą modele aplikacji
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class Model
{
    /**
     * Obiekt połączenia z bazą danych
     *
     * @var \library\Database
     */
    protected $db;

    public function __construct() {
        $this->db = new \library\Database();
    }
}
