<?php

include_once(SERVER_ROOT . '/model/database_model.php');	

class Access_Control{

	//TODO
	/*
	token access system
	additional token cookie for authentication
	hash("sha256", $input);
	$input = $username . $secret;
	$secret = l0nGStr1nGofR@nd0)v(;
	
	move all authentication and cookie stuff to access control
	*/

	public function __construct(){}

	public function redirect_not_admin(){

		if(isset($_COOKIE['username'])){

			$database = new Database_Model;		
	
			if(!$database->is_admin($_COOKIE['username'])){

				header("Location: " . SITE_ROOT . "/index.php?login");
				exit;
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
