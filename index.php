<?php

/**
Main entry point into the application

All http requests land here and are passed to the router.
This file is responsible for setting up the application
wide configuration before control is passed to the router.
*/

// document root of the application files on web server
define('SERVER_ROOT', '/var/www/wseclab');

// url root of the server
define('SITE_ROOT', 'http://10.0.2.15/wseclab');

define('LOGFILE', '/var/log/wseclab/wseclab.log');

set_include_path('/var/www/wseclab');

require_once(SERVER_ROOT . '/controller/router.php');
require_once(SERVER_ROOT . '/util/access_control.php');

?>
