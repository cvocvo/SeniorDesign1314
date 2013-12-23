<?php

class Action_Queue{

	public static function add_action($action, $type, $user, $port){

		$file = fopen(QUEUE_FILE, "a");


		$queue_element = $action . ' ' . $type . ' ' . $user . ' ' . $port . "\n";

		fwrite($file, $queue_element);

		fclose($file);

		chmod(QUEUE_FILE, 0777);

	}

	public static function get_actions(){

		$queue = explode("\n", file_get_contents(QUEUE_FILE));
		file_put_contents(QUEUE_FILE, "");

		return $queue;
	}

}
?>