<?php

/**
Controller for the user_change_password page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This page allows users to change their password
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/database_model.php');
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

        $check_pass = $_POST['password'];

        $dbModel = new Database_Model;
        $user = $_COOKIE['username'];

        if($dbModel->authenticate($user, $check_pass)){
            if($_POST['newpassword'] == $_POST['newpassword2']){

                $result = $dbModel->update_user($user, $_POST['newpassword'], '');
                $success = $result['success'];
                $message = $result['message'];

            }
            else{
                $success = False;
                $message = "Passwords do not match";
            }
        }
        else{

            $success = False;
            $message = 'Failed to authenticate';
        }


        $this->main(array());

        if($success){
            if($message == ""){
                echo '<script>alert("Action completed successfully");</script>';    
            }
            else{
                echo '<script>alert("Action completed successfully\n\nNOTE:' . $message . '");</script>';    
            }
        }
        else{
            echo '<script>alert("Error encountered while performing action\n\nERROR:' . $message . '");</script>';
        }
    }
}

?>

