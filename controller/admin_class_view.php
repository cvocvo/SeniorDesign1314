<?php

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/view.php');

/**
* controller class for the admin class view page
* receives page arguments and interacts with the model
* in order to render the page
*/
class Admin_class_view_Controller{

		

        public $template = 'admin_class_view';

        public function main(array $getVars){
		
			Access_Control::gate_admin_page();
			
			//determine which model is needed

			$view = new View_Model($this->template);
					
			//determine which dynamic variables are needed

			}

}

?>

