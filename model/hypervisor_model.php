<?php

include_once(SERVER_ROOT . '/model/database_model.php');

/**
Model of the hypervisor

Abstracts hypervisor interaction into easy to use functions.
Implements the functionality needed to interact with
VMWare ESXi 5.1 using the VIX command line toolkit.
*/

class Hypervisor_Model{

	public function __construct(){}

/**
Hypervisor Getters
*/

	/*public function get_machines_for_user($user){
		
		$ret = array();

		foreach($this->machines as $machine){
			if($machine['owner'] == $user){
				array_push($ret, $machine);
			}
		}

		return $ret;
	}*/

	/*public function get_base_images(){
		return $this->images;
	}*/

/**
Hypervisor Actions
*/

	private function diff($a1, $a2){

		$ret = array();
		foreach($a1 as $check){
			$good = true;
			foreach($a2 as $used){
				if($check == $used){
					$good = false;
				}
			}
			if($good){
				array_push($ret, $check);
			}
		}

		return $ret;
	}

	private function get_unused_port(){
		$range = array();
		for($i = 5912; $i <= 9900; $i++){
			array_push($range, $i);
		}

		$dbModel = new Database_Model;
		$used = $dbModel->list_used_ports();

		$usedint = array();
		foreach($used as $u){
			array_push($usedint, intval($u));
		}

		foreach($used as $u){
			Logger::log('hypervisor_model', $u);
		}

		$open = $this->diff($range, $used);

		return $open[0];
	}

	public function clone_vm($user, $type){

		$port = $this->get_unused_port();

		$vm_name = $user . "_" . $type;

		$dbModel = new Database_Model;
		$dbModel->vm_set_port($vm_name, $port);

		$type = ($type == "client") ? "defender" : $type;

		//run detached completely from parent process
		//$cmd = 'bash -c "exec nohup setsid php ' . SERVER_ROOT . '/background/clone_fork.php ' . $user . ' ' . $port . ' ' . $type
		//	. ' > /dev/null 2>&1 &"';

		$cmd = //'echo "php ' . SERVER_ROOT . '/background/clone_fork.php ' . $user . ' ' . $port . ' ' . $type . '" | at now'; //> /dev/null 2>/dev/null &';
			'php ' . SERVER_ROOT . '/background/clone_fork.php ' . $user . ' ' . $port . ' ' . $type . ' &> /dev/null &';
		Logger::log('hypervisor_model', $cmd);

		exec($cmd);

		//Logger::log('hypervisor_model', 'cloning script started');

		return;
	}

	public function delete_vm($user, $type){

		$vm_name = $user . "_" . $type;
		
		$dbModel = new Database_Model;
		$dbModel->vm_set_port($vm_name, "NULL");

		$type = ($type == "client") ? "defender" : $type;

		//run detached completely from parent process
		//$cmd = 'bash -c "exec nohup setsid php ' . SERVER_ROOT . '/background/delete_fork.php ' . $user . ' ' . $type
		//	. ' > /dev/null 2>&1 &"';
		$cmd = 'echo "php ' . SERVER_ROOT . '/background/delete_fork.php ' . $user .  ' ' . $type . '" | at now';

		exec($cmd);

		return;
	}

	public function power_on_vm($user, $type){

		$type = ($type == "client") ? "defender" : $type;

		//run detached completely from parent process
		//$cmd = 'bash -c "exec nohup setsid php ' . SERVER_ROOT . '/background/on_fork.php ' . $user . ' ' . $type
		//	. ' > /dev/null 2>&1 &"';

		$cmd = 'echo "php ' . SERVER_ROOT . '/background/poweron_fork.php ' . $user .  ' ' . $type . '" | at now';

		exec($cmd);

		return;
	}

	public function power_off_vm($user, $type){

		$type = ($type == "client") ? "defender" : $type;

		//run detached completely from parent process
		//$cmd = 'bash -c "exec nohup setsid php ' . SERVER_ROOT . '/background/off_fork.php ' . $user . ' ' . $type
		//	. ' > /dev/null 2>&1 &"';

		$cmd = 'echo "php ' . SERVER_ROOT . '/background/poweron_fork.php ' . $user .  ' ' . $type . '" | at now';

		exec($cmd);

		return;
	}


}
