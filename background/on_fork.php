<?php

include_once("../config/config.php");

$ssh = ssh2_connect(HV_HOST, 22);

ssh2_auth_password($ssh, HV_USER, HV_PASSWORD);

$user = $argv[1];
$type = $argv[2];

$stream = ssh2_exec($ssh,
	"/vmfs/volumes/datastore1/wnsl-tools/poweron_" . $type . ".sh " . $user);

sleep(5);

fclose($stream);

?>