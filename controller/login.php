<?php

/**
Controller for the login page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This page gets the users credentials and passes it to the
authentication system. The user is then routed to the
correct landing page if the login was successful.
*/

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
}

?>

