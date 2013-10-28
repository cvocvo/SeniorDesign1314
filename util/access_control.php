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
	
	private static $secret = "fu3gBuY3kcaWN6jnHkepYytAWKRBuMCe";
	
	public function __construct(){}
	
	public static function authenticate($user, $pass){
	
		$database = new Database_Model;
		
		if($database->authenticate($user, $pass)){
			$number_of_days = 365;
			$date_of_expiry = time() + 60 * 60 * 24 * $number_of_days;
			setcookie("username", $user, $date_of_expiry, "/");
			
			setcookie("token", self::make_token($user), $date_of_expiry, "/");
		}
	}
	
	public function logout(){
	
	}
	
	private static function make_token($user){
		return hash("sha256", $user . self::$secret);
	}
	
	private static function check_token($user, $token){
		return hash("sha256", $user . self::$secret) == $token;
	}
	
	public static function is_logged_in(){
		return isset($_COOKIE['username']) && isset($_COOKIE['token'])
			&& self::check_token($_COOKIE['username'], $_COOKIE['token']);
	}
	
	public static function is_admin(){
		$database = new Database_Model;
		return self::is_logged_in() && $database->is_admin($_COOKIE['username']);
	}
	
	public static function redirect_to_landing(){
		
		if(self::is_logged_in()){
			if(self::is_admin()){
				header("Location: " . SITE_ROOT . "/index.php?admin_class_manager");
			}
			else{
				header("Location: " . SITE_ROOT . "/index.php?user_index");
			}
		}
		else{
			header("Location: " . SITE_ROOT . "/index.php?login");
		}
	}
	
	public static function gate_admin_page(){
		
		if(!self::is_admin()){
			http_response_code(403);
			header("Location: " . SITE_ROOT . "/403.php");
			exit();
		}
	}
	
	public static function gate_restricted_page(){
	
		if(!self::is_logged_in()){
			http_response_code(403);
			header("Location: " . SITE_ROOT . "/403.php");
			exit();
		}
	
	}

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
}

?>
