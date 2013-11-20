<?php

/**
This is pink kinda cool yeah
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/util/machine_table_builder.php');

class User_index_Controller
{

    public $template = 'User_index';

    public function main(array $getVars){

        Access_Control::gate_restricted_page();

        $user = $_COOKIE['username'];

        $hvModel = new Hypervisor_Model;

        $machine_tables = array();
        foreach($hvModel->get_machines_for_user($user) as $machine){
        	$table = machine_table_builder::build($machine);
        	if($table != ""){
				array_push($machine_tables, $table);
			}
        }

        $view = new View_Model($this->template);

        $view->assign('machine_tables', $machine_tables);
       
    }

    public function do_post(){
    	
    }

}

?>

