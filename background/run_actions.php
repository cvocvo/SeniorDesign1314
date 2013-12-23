<?php

define('SERVER_ROOT', '/var/www/wseclab');
set_include_path(SERVER_ROOT);

include_once(SERVER_ROOT . "/config/config.php");
include_once(SERVER_ROOT . "/util/action_queue.php");

$actions = Action_Queue::get_actions();

foreach ($actions as $action) {

	$parsed = explode(" ", $action);

	//action type user port
	$script = '';
	if($parsed[0] == 'clone'){
		$script = 'clone_fork.php';
	}
	elseif ($parsed[0] == 'poweron') {
		$script = 'on_fork.php';
	}
	elseif ($parsed[0] == 'poweroff'){
		$script = 'off_fork.php';
	}
	elseif($parsed[0] == 'delete'){
		$script = 'delete_fork.php';
	}
		
	$type = $parsed[1];

	$user = $parsed[2];

	$port = ($parsed[0] == 'clone') ? $parsed[3] : '';

	$cmd = "php " . SERVER_ROOT . "/background/" . $script . " " . $user . " " . $type . " " . $port;

	echo $cmd;
	exec($cmd);

}

?>