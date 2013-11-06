<?php

include_once(SERVER_ROOT . '/util/access_control.php');
include_once(SERVER_ROOT . '/model/view.php');

class User_index_Controller
{

        public $template = 'User_index';

        public function main(array $getVars){

                Access_Control::gate_restricted_page();

                //determine which model is needed

                $view = new View_Model($this->template);

                //determine which dynamic variables are needed

        }

}

?>

