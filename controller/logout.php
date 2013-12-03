<?php

/**
Controller for the logout page

This page logs a user out if they are currently logged in.
They are then redirected to the login page.
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/util/logger.php');	

class Logout_Controller{

	public function main(){
	
		Access_Control::logout();

		Logger::log("logout", $_COOKIE['username'] . ":" . $_COOKIE['token']);
	
		Access_Control::redirect_to_login();
	}

}
