<?php

define('SERVER_ROOT', '/var/www/wseclab');
set_include_path(SERVER_ROOT);

include_once(SERVER_ROOT . "/config/config.php");
include_once(SERVER_ROOT . "/util/logger.php");

Logger::log('clone_fork', 'the thing is going');

$ssh = ssh2_connect(HV_HOST, 22);

ssh2_auth_password($ssh, HV_USER, HV_PASSWORD);

$user = $argv[1];
$port = $argv[2];
$type = $argv[3];

$stream = ssh2_exec($ssh,
	"/vmfs/volumes/datastore1/wnsl-tools/clone_" . $type . ".sh " . $user . " " . $port);

Logger::log('clone_fork', 'the ssh went');


sleep(180);

Logger::log('clone_fork', 'slept');

fclose($stream);

?>