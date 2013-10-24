<?php

include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/view.php');

class Login_Controller{	

	public $template = 'login';

	public function main(array $getVars){

			//determine which model is needed

			$view = new View_Model($this->template);

			//determine which dynamic variables are needed

	}

	public function do_post(){

//		include_once(SERVER_ROOT . '/model/database_model.php');

//		session_start();
		//only login if they agree not to break ALL the laws
		if(isset($_POST['agree'])){
			
			$user = $_POST['username'];			
			$number_of_days = 365;
			$date_of_expiry = time() + 60 * 60 * 24 * $number_of_days;
			setcookie("username", $_POST['username'], $date_of_expiry, "/");
			$pass = $_POST['password'];

			$database = new Database_Model;

			if($database->authenticate($user, $pass)){

//				$_SESSION['user'] = $user;
		
				if($database->is_admin($user)){
					// set cookie is admin
					$this->admin_logged_in();
//					$_SESSION['is_admin'] = True;
				}
				else{
					// set cookie is not admin
					$this->user_logged_in();
//					$_SESSION['is_admin'] = False;
				}
			}
			else{
				$this->loopback();
			}
		}
		else{
			$this->loopback();
		}

	}

	private function loopback(){
		header("Location: " . SITE_ROOT . "/index.php?login");
		exit;
	}

	private function admin_logged_in(){
		header("Location: " . SITE_ROOT . "/index.php?admin_class_manager");
	}

	private function user_logged_in(){
		header("Location: " . SITE_ROOT . "/index.php?user_index");
	}

}

?>

