<?php

class User_index_Controller
{


        public $template = 'User_index';

        public function main(array $getVars){

		include_once(SERVER_ROOT . '/util/access_control.php');

                $access = new Access_Control;
                $access->redirect_not_logged_in();


                //determine which model is needed

                $view = new View_Model($this->template);

                //determine which dynamic variables are needed

        }

}

?>

