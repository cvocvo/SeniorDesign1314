<?php

include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/util/access_control.php');

class Login_Controller{	

	public $template = 'login';

	public function main(array $getVars){

			//determine which model is needed

			$view = new View_Model($this->template);

			//determine which dynamic variables are needed

	}

	public function do_post(){

		//only login if they agree not to break ALL the laws
		if(isset($_POST['agree'])){
			Access_Control::authenticate($_POST['username'], $_POST['password']);
		}
		
		Access_Control::redirect_to_landing();

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

