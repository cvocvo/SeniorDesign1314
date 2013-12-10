<?php

/**
Controller for the admin_student_view page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This page allows an admin to view an individual student
in more detail, as well as change their information,
and view and manage their virtual machines.
*/

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
        		$table = Machine_table_builder::build($machine, $getVars['student'], "admin_student_view");
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
        $message = "";

        $student = $_POST['student'];

        //edit student form
        if(isset($_POST['form_id']) && $_POST['form_id'] == 'edit_student'){

            $dbModel = new Database_Model;

            //$action = $_POST['action'];
            /*if($action == 'delete'){

                $result = $dbModel->delete_user($student);
                $success = $result['success'];
                $message = $result['message'];

            }*/
            //else{
            $class = $_POST['class'];

            if($_POST['newpassword'] != '' &&
                $_POST['newpassword'] == $_POST['newpassword2']){

                $pass = $_POST['newpassword'];
                $result = $dbModel->update_user($student, $pass, $class);
                $success = $result['success'];
                $message = $result['message'];   
            }

            elseif($_POST['newpassword'] != '' &&

                $_POST['newpassword'] != $_POST['newpassword2']){
                $success = false;
                $message = "Passwords do not match";
            }

            else{

                $result = $dbModel->update_user($student, '', $class);
                $success = $result['success'];
                $message = $result['message'];   
            }

            //}
        }

        //machine action form
        elseif(isset($_POST['machine'])){
            $machine = $_POST['machine'];
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

        }

        else{
            $success = False;
            $error = 'Invalid Form Input';
        }
        
        //re render page with status notification
        $this->main(array('student' => $student));

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

