<?php

class Logout_Controller{


	public function main(){

//		session_destroy();
		
			$number_of_days = 365;
			$date_of_expiry = time() -2592000 * $number_of_days;
			setcookie("username", $_COOKIE['username'], $date_of_expiry, "/");
		$this->redirect();	


	}

	private function redirect(){
                header("Location: " . SITE_ROOT . "/index.php?login");
                exit;
        }

	

}
