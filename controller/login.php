<?php

class Login_Controller
{


        public $template = 'login';

        public function main(array $getVars){

                //determine which model is needed

                $view = new View_Model($this->template);

                //determine which dynamic variables are needed

        }

	public function do_post(){

		include_once(SERVER_ROOT . '/model/database_model.php');

		//only login if they agree not to break ALL the laws
		if(isset($_POST['agree'])){
			$user = $_POST['username'];
			$pass = $_POST['password'];

			$database = new Database_Model;

			if($database->authenticate($user, $pass)){
				if($database->is_admin($user)){
					$this->admin_logged_in();
				}
				else{
					$this->user_logged_in();
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
		header("Location: " . SITE_ROOT . "/index.php?admin_tools");
	}

	private function user_logged_in(){
		header("Location: " . SITE_ROOT . "/index.php?user_index");
	}

}

?>

