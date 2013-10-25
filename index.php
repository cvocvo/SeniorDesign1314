<?php

/**

Main entry point into the application
passes query string and args to router to map to correct
page

contains application wide config

*/

// document root of the application files on web server
define('SERVER_ROOT', '/var/www/wseclab');

// url root of the server
define('SITE_ROOT', 'http://129.186.215.213/wseclab');

set_include_path('/var/www/wseclab');

require_once(SERVER_ROOT . '/controller/router.php');
require_once(SERVER_ROOT . '/util/access_control.php');

define('LOGFILE', '/var/log/wseclab/wseclab.log');

?>
