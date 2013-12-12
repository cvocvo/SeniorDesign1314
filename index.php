<?php

/**
Main entry point into the application

All http requests land here and are passed to the router.
This file is responsible for setting up the application
wide configuration before control is passed to the router.
*/

// document root of the application files on web server
define('SERVER_ROOT', '/var/www/wseclab');

set_include_path(SERVER_ROOT);

require_once(SERVER_ROOT . '/config/config.php');
require_once(SERVER_ROOT . '/controller/router.php');
require_once(SERVER_ROOT . '/util/access_control.php');

?>
