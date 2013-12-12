<?php

/**
Controller for the admin_tools page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This page allows an admin to view and manage their own
profile as well as all other admins in the system. They
are also able to add and remove admins from this page.
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');

class Admin_tools_Controller{

    public $template = 'admin_tools';

    public function main(array $getVars){
	
		Access_Control::gate_admin_page();

	    $dbModel = new database_Model;
	    $hvModel = new Hypervisor_Model;
		
		$view = new View_Model($this->template);

		$admins = $dbModel->list_admins();

		foreach($admins as $name=>$data){
			$admins[$name]['is_me'] = $_COOKIE['username'] == $name;
		}

		$view->assign('user', $_COOKIE['username']);
		$view->assign('admins', $admins);
		$view->assign('nonadmins', $dbModel->list_nonadmins());

    }

    public function do_post(){

    	$success = False;
    	$message = "";

    	$form = $_POST['form_id'];

        $dbModel = new database_Model;

    	if($form == 'edit_details'){
    		$user = $_POST['user'];

            if($_POST['newpassword'] == $_POST['newpassword2']){

                
                $pass = $_POST['newpassword'];
                $class = "default";
                $is_admin = true;

                $result = $dbModel->update_user($user, $pass, '');
                $success = $result['success'];
                $message = $result['message'];
            }
            else{
                $success = False;
                $message = "Passwords do not match";
            }
    	}

    	elseif($form == 'remove_admin'){
            $user = $_POST['username'];
            $result = $dbModel->demote_admin($user);
    		$success = $result['success'];
            $message = $result['message'];
    	}

    	elseif($form == 'add_admin'){
    		$user = $_POST['username'];
            $result = $dbModel->promote_user_to_admin($user);
            $success = $result['success'];
            $message = $result['message'];
    	}

    	elseif($form == 'create_admin'){
			if($_POST['newpassword'] == $_POST['newpassword2']){

                $name = $_POST['name'];
                if(ctype_alnum($name)){
                    $pass = $_POST['newpassword'];
                    $class = "default";
                    $is_admin = true;

                    $result = $dbModel->create_user($name, $pass, $class, $is_admin);
                    $success = $result['success'];
                    $message = $result['message'];

                    /*if($result['success']){
                        $result = $dbModel->make_vms_for_user($name);
                        $success &= $result['success'];
                        $message .= $result['message'];
                        if($result['message'] != ''){
                            $message .= '\n';
                        }
                    }*/
                }
                else{
                    $success = False;
                    $message = 'Name must be alphanumeric';
                }
            }
            else{
                $success = False;
                $message = "Passwords do not match";
            }
    	}

    	$this->main(array());

    	if($success){
            if($message == ''){
                echo '<script>alert("Action completed successfully");</script>';    
            }
            else{
                echo '<script>alert("Action completed successfully\n\nNOTE: ' . $message . '");</script>';  
            }
        }
        else{
            echo '<script>alert("Error encountered while performing action\n\nERROR:' . $message . '");</script>';
        }
    	
    }

}

?>

