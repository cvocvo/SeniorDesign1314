<?php

class User_change_password_Controller
{


        public $template = 'user_change_password';

        public function main(array $getVars){


		include_once(SERVER_ROOT . '/util/access_control.php');

                $access = new Access_Control;
                $access->redirect_not_logged_in();

                //determine which model is needed

                $view = new View_Model($this->template);

                //determine which dynamic variables are needed

        }

	public function do_post(){

		include_once(SERVER_ROOT . '/model/databse_model.php');

		// change passwords n such


	}

}

?>

