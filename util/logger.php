<?php

class Logger{
	
	public static function log($source, $message){
		if(LOGGING_ON){
			$file = fopen(LOGFILE, "a");
			
			if($file){
				$now = date("m/d/y H:i:s");
				fwrite($file, "[" . $now . " | " . $source . "] " . $message . "\n");
				fclose($file);
			}
		}
	
	}

	public static function log_post($source, $post_body){
		if(LOGGING_ON){
			$post_dump = "POST{ ";
			foreach($post_body as $key => $value){
				$post_dump .= $key . ":" . $value . " ";
			}
			$post_dump .= "}";

			self::log($source, $post_dump);
		}
	}
}

?>