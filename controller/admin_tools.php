<?php

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

		$view->assign('admins', $admins);
		$view->assign('nonadmins', $dbModel->list_nonadmins());

    }

    public function do_post(){
    	
    }

}

?>

