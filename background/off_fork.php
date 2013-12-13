<?php

include_once("../config/config.php");

$ssh = ssh2_connect(HV_HOST, 22);

ssh2_auth_password($ssh, HV_USER, HV_PASSWORD);

$user = $arg[1];
$type = $arg[3];

$stream = ssh2_exec($ssh,
	"/vmfs/volumes/datastore1/wnsl-tools/poweroff_" . $type . ".sh " . $user);

sleep(12);

fclose($stream);

?>