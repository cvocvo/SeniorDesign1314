<?php

/**
Controller for the admin_class_view page

Generates all dynamic content on GETs to the page, and
handles form processing on POSTs to the page

This page allows an admin to view and edit the details
of an individual class, as well as view and edit members
of the class. The admin can select an individual student
to view in more detail.
*/

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/database_model.php');
include_once(SERVER_ROOT . '/model/hypervisor_model.php');
include_once(SERVER_ROOT . '/model/view.php');
include_once(SERVER_ROOT . '/util/logger.php');

/**
* controller class for the admin class view page
* receives page arguments and interacts with the model
* in order to render the page
*/
class Admin_class_view_Controller{
		

    public $template = 'admin_class_view';

    public function main(array $getVars){

		Access_Control::gate_admin_page();
		
		$dbModel = new Database_Model;
		$hvModel = new Hypervisor_Model;

		if(isset($getVars['class']) && $dbModel->is_class($getVars['class'])){

			$view = new View_Model($this->template);

			$hvModel->get_base_images();
			$dbModel->list_students_in_class($getVars['class']);

			$view->assign('class', $getVars['class']);
			$view->assign('students', $dbModel->list_students_in_class($getVars['class']));
			$view->assign('images', $dbModel->list_vm_types());
			$view->assign('user', $_COOKIE['username']);
			$view->assign('class_images',
				$dbModel->list_vm_types_for_class($getVars['class']));

		}
		else{

			header("Location: " . SITE_ROOT . "/404.php");
			exit();
		}
			
	}

	public function do_post(){
		
		$success = False;
		$message = "";

		$class = (isset($_POST['class'])) ? $_POST['class'] : "";
		$form_id = $_POST['form_id'];

		$dbModel = new Database_Model;

		if($form_id == 'class'){
			Logger::log('admin_class_view', 'class form active');
			$action = (isset($_POST['action'])) ? $_POST['action'] : "";

			//save
			if($action == 'save'){

				$chosen = array();
				foreach($images as $image){
					if(array_key_exists($image, $_POST)){
						array_push($chosen, $image);
					}
				}

				$result = $dbModel->update_class($class, $chosen);
				$success = $result['success'];
				$message = $result['message'];


				/*
				$result = $dbModel->delete_vm_types_from_class($class);
				
				if($success){
					$images = $dbModel->list_vm_types();
					
							$result = $dbModel->add_vm_type_to_class($class, $image);
							$success &= $result['success'];
							$message .= $result['message'];
							if($result['message'] != ''){
								$message .= '\n';
							}
						}
					}
				}*/
			}

			//renew
			elseif ($action == 'renew') {
				Logger::log('admin_class_view', 'renewing machines for class');
				$success = True;
			}

			//power_down_vms
			elseif ($action == 'power_down_vms') {
				Logger::log('admin_class_view', 'powering down vms for class');
				$success = True;
			}

			//delete_vms
			elseif ($action == 'delete_vms') {
				Logger::log('admin_class_view', 'deleting vms for class');
				$success = True;
			}

			else{
				$message = "Unknown Action";
			}
		}

		else{

			$dbModel = new Database_Model;
			$result = $dbModel->delete_user($_POST['student']);
			$success = $result['success'];
			$message = $result['message'];

		}

		//re render page with status notification
		$this->main(array('class' => $class));

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

