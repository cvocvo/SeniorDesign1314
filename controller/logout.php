<?php

class Logout_Controller{


	public function main(){

		session_destroy();

		$this->redirect();	


	}

	private function redirect(){
                header("Location: " . SITE_ROOT . "/index.php?login");
                exit;
        }

	

}
