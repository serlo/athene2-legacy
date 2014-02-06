<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

set_time_limit(20);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
set_error_handler("exception_error_handler");
date_default_timezone_set('Europe/Berlin');

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();


// catch catchable fatal errors
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}