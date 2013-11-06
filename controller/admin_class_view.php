<?php

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

}

?>

