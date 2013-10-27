<?php

class Logger{
	
	public static function log($source, $message){
		$file = fopen(LOGFILE, "a");
		
		if($file){
			$now = date("m/d/y H:i:s");
			fwrite($file, "[" . $now . " | " . $source . "] " . $message . "\n");
			fclose($file);
		}
	
	}
}

?>