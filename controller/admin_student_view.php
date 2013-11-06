<?php

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/util/logger.php');

class Admin_student_view_Controller
{


        public $template = 'admin_student_view';

        public function main(array $getVars){

			
			Access_Control::gate_admin_page();

			$dbModel = new Database_Model;
			$hvModel = new Hypervisor_Model;

            if(isset($getVars['student']) && $dbModel->is_user($getVars['student'])){


	            $view = new View_Model($this->template);

	            $view->assign('machines', $hvModel->get_machines_for_user($getVars['student']));
	            $view->assign('classes', $db->list_classes());
	        }



            //determine which dynamic variables are needed

        }

        private $online_template = '

        ';

        private $offline_template = '

        ';

        private $not_deployed_template = '

        ';

        private function build_machine_table

}

?>

