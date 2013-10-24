<?php

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/view.php');

class Admin_tools_Controller
{


        public $template = 'admin_tools';

        public function main(array $getVars){

		include_once(SERVER_ROOT . '/util/access_control.php');

		$access = new Access_Control;
		$access->redirect_not_admin();
		//determine which model is needed

		$view = new View_Model($this->template);

		//determine which dynamic variables are needed

        }

}

?>

