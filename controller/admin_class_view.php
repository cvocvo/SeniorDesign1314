<?php

/**
Controller for the admin_class_view page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This page allows an admin to view and edit the details
of an individual class, as well as view and edit members
of the class. The admin can select an individual student
to view in more detail.
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/util/logger.php');

/**
* controller class for the admin class view page
* receives page arguments and interacts with the model
* in order to render the page
*/
class Admin_class_view_Controller{
		

    public $template = 'admin_class_view';

    public function main(array $getVars){

		Access_Control::gate_admin_page();
		
		$dbModel = new Database_Model;
		$hvModel = new Hypervisor_Model;

		if(isset($getVars['class']) && $dbModel->is_class($getVars['class'])){

			$view = new View_Model($this->template);

			$hvModel->get_base_images();
			$dbModel->list_students_in_class($getVars['class']);

			$view->assign('class', $getVars['class']);
			$view->assign('students', $dbModel->list_students_in_class($getVars['class']));
			$view->assign('images', $hvModel->get_base_images());

		}
		else{

			header("Location: " . SITE_ROOT . "/404.php");
			exit();
		}
			
	}

	public function do_post(){
		
		$success = False;
		$error = "";

		$class = (isset($_POST['class'])) ? $_POST['class'] : "";
		$action = (isset($_POST['action'])) ? $_POST['action'] : "";

		//save
		if($action == 'save'){
			Logger::log('admin_class_view', 'saving changes to class');
			$success = True;
		}

		//renew
		elseif ($action == 'renew') {
			Logger::log('admin_class_view', 'renewing machines for class');
			$success = True;
		}

		//power_down_vms
		elseif ($action == 'power_down_vms') {
			Logger::log('admin_class_view', 'powering down vms for class');
			$success = True;
		}

		//delete_vms
		elseif ($action == 'delete_vms') {
			Logger::log('admin_class_view', 'deleting vms for class');
			$success = True;
		}

		else{
			$error = "Unknown Action";
		}

		//re render page with status notification
		$this->main(array('class' => $class));

		if($success){
			echo '<script>alert("Action completed successfully");</script>';	
		}
		else{
			echo '<script>alert("Error encountered while performing action\n\nERROR:' . $error . '");</script>';
		}
	}

}

?>

