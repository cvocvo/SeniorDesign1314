<?php

define('SERVER_ROOT', '/var/www/wseclab');
set_include_path(SERVER_ROOT);

include_once(SERVER_ROOT . "/config/config.php");
include_once(SERVER_ROOT . "/util/logger.php");
include_once(SERVER_ROOT . "/model/database_model.php");

Logger::log('clone_fork', 'the thing is going');

$ssh = ssh2_connect(HV_HOST, 22);

ssh2_auth_password($ssh, HV_USER, HV_PASSWORD);

$user = $argv[1];
$port = $argv[3];
$type = $argv[2];

$stream = ssh2_exec($ssh,
	"/vmfs/volumes/datastore1/wnsl-tools/clone_" . $type . ".sh " . $user . " " . $port);

Logger::log('clone_fork', 'the ssh went');


sleep(180);

if($type = 'attacker'){
	sleep(240);
}

Logger::log('clone_fork', 'slept');

fclose($stream);

$vm_name = ($type = 'defender') ? $user . '_client' : $user . '_' . $type;

$dbModel = new Database_Model;
$dbModel->vm_set_state($vm_name, 'online');

?>