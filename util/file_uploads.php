<?php

include_once(SERVER_ROOT . '/util/logger.php');

class File_Uploads{

	public static function exists(){
		return $_FILES["file"]["error"] == 0;
	}

	public static function get_users(){

		$file = $_FILES['file']['tmp_name'];

		$array = preg_split("/\W/", file_get_contents($file));

		$array = array_slice($array, 0, count($array) - 1);

		return $array;
		
		/*
		if ($_FILES["file"]["error"] > 0)
		{
			Logger::log('file_uploader', "Error: " . $_FILES["file"]["error"] . "<br>");
		}
		else
		{
			Logger::log('file_uploader', "Upload: " . $_FILES["file"]["name"]);
			Logger::log('file_uploader', "Type: " . $_FILES["file"]["type"]);
			Logger::log('file_uploader', "Size: " . $_FILES["file"]["size"]);
			Logger::log('file_uploader', "Stored in: " . $_FILES["file"]["tmp_name"]);
		}*/
	}

}

?>