<?php

include_once("../config/config.php");

$ssh = ssh2_connect(HV_HOST, 22);

ssh2_auth_password($ssh, HV_USER, HV_PASSWORD);

$user = $argv[1];
$port = $argv[2];
$type = $argv[3];

$stream = ssh2_exec($ssh,
	"/vmfs/volumes/datastore1/wnsl-tools/clone_" . $type . ".sh " . $user . " 7274");// . $port);

sleep(180);

fclose($stream);

?>