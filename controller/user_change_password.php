<?php

/**
Controller for the user_change_password page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This page allows users to change their password
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/databse_model.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/util/logger.php');

class User_change_password_Controller{

    public $template = 'user_change_password';

    public function main(array $getVars){

        Access_Control::gate_restricted_page();
        
        $view = new View_Model($this->template);
    }

    public function do_post(){

        $success = False;
        $message = "";

        Logger::log('user_change_password', 'edit user details');

        $success = True;

        $this->main(array());

        if($success){
    		echo '<script>alert("Action completed successfully");</script>';	
    	}
    	else{
    		echo '<script>alert("Error encountered while performing action\n\nERROR:' . $error . '");</script>';
    	}
    }

}

?>

