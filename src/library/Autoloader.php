<?php

namespace library;

/**
 * Klasa do dynamicznego ładowania potrzebnych plików z klasami
 *
 * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
 */
class Autoloader
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Ładowanie klas
     *
     * Obrabia nazwę klasy i includuje wymagany plik
     *
     * @author Paweł Rutkowski <rutkowski.pl@gmail.com>
     *
     * @param String $class
     *
     */
    private function autoload($class)
    {
        $class = ltrim($class, '\\');
        $includeClassFilePath  = '';
        $className = '';
        $namespace = '';

       $lastNamespaceStringPosition = strripos($class, '\\');

        if ($lastNamespaceStringPosition)
        {
            $namespace = substr($class, 0, $lastNamespaceStringPosition);
            $className = substr($class, $lastNamespaceStringPosition + 1);
            $includeClassFilePath  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $includeClassFilePath .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require $includeClassFilePath;
    }
}
