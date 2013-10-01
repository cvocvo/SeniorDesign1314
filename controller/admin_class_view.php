<?php


/**
* controller class for the admin class view page
* receives page arguments and interacts with the model
* in order to render the page
*/
class Admin_class_view_Controller
{


        public $template = 'admin_class_view';

        public function main(array $getVars){

                //determine which model is needed

                $view = new View_Model($this->template);
                
		//determine which dynamic variables are needed

        }

}

?>

