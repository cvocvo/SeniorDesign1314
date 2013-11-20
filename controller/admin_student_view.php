<?php

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/util/logger.php');
include_once(SERVER_ROOT . '/util/machine_table_builder.php');

class Admin_student_view_Controller{

    public $template = 'admin_student_view';

    public function main(array $getVars){
		
		Access_Control::gate_admin_page();

		$dbModel = new Database_Model;
		$hvModel = new Hypervisor_Model;

        if(isset($getVars['student']) && $dbModel->is_user($getVars['student'])){

        	$machine_tables = array();
        	foreach($hvModel->get_machines_for_user($getVars['student']) as $machine){
        		$table = Machine_table_builder::build($machine);
        		if($table != ""){
        			array_push($machine_tables, $table);
        		}
        	}

            $view = new View_Model($this->template);

            $view->assign('user', $getVars['student']);
            $view->assign('class', $dbModel->get_class_for_user($getVars['student']));
            $view->assign('classes', $dbModel->list_classes());
            $view->assign('machine_tables', $machine_tables);
            
        }
        else{
        	header("Location: " . SITE_ROOT . "/404.php");
			exit();
        }

    }

    public function do_post(){
    
    	$success = False;
        $error = "";

        //edit student form
        if(isset($_POST['student'])){
            $student = $_POST['student'];

            $success = True;
            Logger::log('admin_student_view', 'editing student details');
        }

        //machine action form
        elseif(isset($_POST['machine'])){
            $machine = $_POST['machine'];
            $action = (isset($_POST['action'])) ? $_POST['action'] : "";

            if($action == 'power_off'){

            }

            elseif ($action == 'power_on') {
                # code...
            }

            elseif ($action == 'delete'){

            }

            elseif ($action == 'deploy') {
                # code...
            }

            else{

            }

        }

        else{
            $success = False;
            $error = 'Invalid Form Input';
        }
        
        //re render page with status notification
        $this->main(array('student' => $student));

        if($success){
            echo '<script>alert("Action completed successfully");</script>';    
        }
        else{
            echo '<script>alert("Error encountered while performing action\n\nERROR:' . $error . '");</script>';
        }
    
    }

}

?>

