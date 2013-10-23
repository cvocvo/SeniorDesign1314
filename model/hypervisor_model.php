<?php

class Hypervisor_Model{

	public function __construct(){}

	private $machines = array(
		array(
			'name' => 'matt_attack',
			'status' => 'on',
			'time_remaining' => '16:16:16',
			'ip_address' => '1.2.3.4',
			'owner' => 'matt'
		),
		array(
			'name' => 'matt_client',
			'status' => 'off',
			'owner' => 'matt'
		),
		array(
			'name' => 'matt_usrp',
			'status' => 'not imaged',
			'owner' => 'matt'
		)
	);

	public function get_machines_for_user($user){
		
		$ret = array();

		foreach($machine in $this->machines){
			if(machine['owner'] == $user){
				array_push($ret, machine);
			}
		}

		return $ret;
	}






}
