<?php


class Access_Control{

	public function __construct(){}

	public function redirect_not_admin(){

		if(!isset($_COOKIE['username'])){

			$database = new Database_Model;		
	
			if(!$database->is_admin($_COOKIE['username'])){

				header("Location: " . SITE_ROOT . "/index.php?login");
				exit;
			}
		}
	}

	public function redirect_not_logged_in(){
		if(!isset($_COOKIE['username'])){
			header("Location: " . SITE_ROOT . "/index.php?login");
			exit;
		}
	}

}

?>
