<?php

/**
* controller class for the admin class manager page
* receives page arguments and interacts with the model
* to render the page
*/
class Admin_class_manager_Controller
{


	public $template = 'admin_class_manager';

	public function main(array $getVars){

		//determine which model is needed


		$view = new View_Model($this->template);

		//determine which dynamic variables are needed
		
	}

}

?>
