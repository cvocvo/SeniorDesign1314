<?php


// url root of the server
define('SITE_ROOT', 'http://10.0.2.15/wseclab');

// logging control, not the process must have write access to the logfile
define("LOGGING_ON", True);
define('LOGFILE', '/var/log/wseclab/wseclab.log');

// mysql database config
// required tables can be loaded with the myqsldumpfile.sql
define('DB_HOST', 'localhost');
define('DB_NAME', 'wseclab');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');

// esxi host ssh information
define("HV_HOST", "129.186.215.200");
define("HV_USER", "root");
define("HV_PASSWORD", "X8DLT-3F");

define("QUEUE_FILE", "/tmp/queue");

?>