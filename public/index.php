<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));


/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
 $config = new Zend_Config(
    array(
        'database' => array(
            'adapter' => 'Mysqli',
            'params'  => array(
                'host'     => '127.0.0.1',
                'dbname'   => 'test',
                'username' => 'root',
                'password' => '',
            )
        )
    )
);
 
$db = Zend_Db::factory($config->database);


Zend_Registry::set('db', $db);
$application->bootstrap()
            ->run();