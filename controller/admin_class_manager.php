<?php

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/model/view.php');

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
}

?>
