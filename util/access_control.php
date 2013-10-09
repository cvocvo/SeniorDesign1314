<?php


class Access_Control{

	public function __construct(){}

	public function redirect_not_admin(){

	include_once(SERVER_ROOT . '/model/database_model.php');	

		if(isset($_COOKIE['username'])){

			$database = new Database_Model;		
	
			if(!$database->is_admin($_COOKIE['username'])){

				header("Location: " . SITE_ROOT . "/index.php?login");
				exit;
			}
			else{
				header("Location: " . SITE_ROOT . "/index.php?user_change_password");
			}
		}
		else{
			$this->redirect_not_logged_in();
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
