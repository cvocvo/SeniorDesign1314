<?php

include_once(SERVER_ROOT . '/util/access_control.php');

class Logout_Controller{

	public function main(){
	
		Access_Control::logout();
	
		Access_Control::redirect_to_landing();

//		session_destroy();
		
		


	}

}
