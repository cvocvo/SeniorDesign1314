<?php

define('SERVER_ROOT', '/var/www/wseclab');
set_include_path(SERVER_ROOT);

include_once(SERVER_ROOT . "/config/config.php");
include_once(SERVER_ROOT . "/model/database_model.php");

$ssh = ssh2_connect(HV_HOST, 22);

ssh2_auth_password($ssh, HV_USER, HV_PASSWORD);

$user = $argv[1];
$type = $argv[2];

$stream = ssh2_exec($ssh,
	"/vmfs/volumes/datastore1/wnsl-tools/delete_" . $type . ".sh " . $user);

sleep(10);

fclose($stream);

$vm_name = ($type = 'defender') ? $user . '_client' : $user . '_' . $type;

$dbModel = new Database_Model;
$dbModel->vm_set_state($vm_name, 'not_deployed');

?>