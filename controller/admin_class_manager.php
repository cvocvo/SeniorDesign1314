<?php

/**
Controller for the admin_class_manager page

Generates all dynamic content on GETs to this page, and
handles form processing on POSTs to the page.

This page allows an admin to create and delete classes, as
well as select an individual class to view in more detail.
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/util/logger.php');	

/**
* controller class for the admin class manager page
* receives page arguments and interacts with the model
* to render the page
*/
class Admin_class_manager_Controller{

	public $template = 'admin_class_manager';

	public function main(array $getVars){

		Access_Control::gate_admin_page();

		//determine which model is needed
		$dbModel = new Database_Model;
		$hvModel = new Hypervisor_Model;

		$view = new View_Model($this->template);

		//determine which dynamic variables are needed

		$view->assign('classes', $dbModel->list_classes());
		$view->assign('base_images', $hvModel->get_base_images());
		
	}

	public function do_post(){

		$success = False;
		$message = "";

		$form = (isset($_POST['form_id'])) ? $_POST['form_id'] : "";

		$dbModel = new Database_Model;

		//delete_class
		if($form == 'delete_class'){
			$class = $_POST['class'];
			$result = $dbModel->delete_class($class);
			$success = $result['success'];
			$message = $result['message'];
		}

		//create_class
		elseif($form == 'create_class') {
			//part 1, create class
			$name = $_POST['name'];
			if(ctype_alnum($name)){
				$result = $dbModel->create_class($name);
				$success = $result['success'];
				$message = $result['message'];
			}
			else{
				$success = False;
				$message = 'Name must be alphanumeric';
			}
			//part 2, use upload file if present to create
			//users in the class
		}

		//add_student_to_class
		elseif($form == 'add_student_to_class'){
			if($_POST['newpassword'] == $_POST['newpassword2']){

				$name = $_POST['name'];
				if(ctype_alnum($name)){
					$pass = $_POST['newpassword'];
					$class = $_POST['class'];
					$is_admin = false;

					$result = $dbModel->create_user($name, $pass, $class, $is_admin);
					$success = $result['success'];
					$message = $result['message'];
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

		else{
			$success = False;
			$message = "Unknown Action";
		}

		//re render page with status notification
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
