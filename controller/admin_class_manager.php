<?php

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
		$error = "";

		$form = (isset($_POST['form_id'])) ? $_POST['form_id'] : "";

		//delete_class
		if($form == 'delete_class'){
			Logger::log('admin_class_manager', 'deleting class');
			$success = True;
		}

		//create_class
		elseif($form == 'create_class') {
			Logger::log('admin_class_manager', 'creating class');
			$success = True;
		}

		//add_student_to_class
		elseif($form == 'add_student_to_class'){
			Logger::log('admin_class_manager', 'adding student to class');
			$success = False;
			$error = 'test error';
		}

		//re render page with status notification
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
