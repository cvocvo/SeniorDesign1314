<?php

/**
Controller for the admin_class_view page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This is the main point of interaction for students. This
page allows students to deploy and manage their own virtual
machines.
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/util/machine_table_builder.php');
include_once(SERVER_ROOT . '/model/database_model.php');

class User_index_Controller
{

    public $template = 'User_index';

    public function main(array $getVars){

        Access_Control::gate_restricted_page();

        $user = $_COOKIE['username'];

        $hvModel = new Hypervisor_Model;
        $dbModel = new Database_Model;

        $machine_tables = array();
        foreach($dbModel->list_vms_for_user($user) as $machine){
        	$table = machine_table_builder::build($machine, $user, "user_index");
        	if($table != ""){
				array_push($machine_tables, $table);
			}
        }

        $view = new View_Model($this->template);

        $view->assign('machine_tables', $machine_tables);
       
    }

    public function do_post(){

        $success = False;
        $message = "";

        $machine = (isset($_POST['machine'])) ? $_POST['machine'] : "";
        $action = (isset($_POST['action'])) ? $_POST['action'] : "";

        if($action == 'power_off'){
            $success = True;

            Logger::log('admin_student_view', 'power off');
        }

        elseif ($action == 'power_on') {
            $success = True;

            Logger::log('admin_student_view', 'power on');
        }

        elseif ($action == 'delete'){
            $success = True;

            Logger::log('admin_student_view', 'delete');
        }

        elseif ($action == 'deploy') {
            $success = True;
            $message = 'Cloning may take up to 30 minutes';

            Logger::log('admin_student_view', 'deploy');
        }

        else{

        }
        
        //re render page with status notification
        $this->main(array());

        if($success){
            if($message == ""){
                echo '<script>alert("Action completed successfully");</script>';    
            }
            else{
                echo '<script>alert("Action completed successfully\n\nNOTE:' . $message . '");</script>';    
            }
        }
        else{
            echo '<script>alert("Error encountered while performing action\n\nERROR:' . $message . '");</script>';
        }
    
    	
    }

}

?>

