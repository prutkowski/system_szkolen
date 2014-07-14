<?php

//Define application path
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));

defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));

defined('VIEWS_PATH')
    || define('VIEWS_PATH', realpath(dirname(__FILE__) . '/../app/views'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

set_include_path(get_include_path() . PATH_SEPARATOR . APPLICATION_PATH . DIRECTORY_SEPARATOR . '../');

//load configuration configuration files
//$config = parse_ini_file(APPLICATION_PATH . '/conf/application.ini');
require_once LIBRARY_PATH . DIRECTORY_SEPARATOR . 'Autoloader.php';
$autoloader  = new library\Autoloader();

$application = library\FrontController::getInstance();
$application->run();

