<?php

class Hypervisor_Model{

	public function __construct(){}

	private $machines = array(
		array(
			'name' => 'matt_attack',
			'status' => 'online',
			'time_remaining' => '16:16:16',
			'address' => '1.2.3.4',
			'owner' => 'matt'
		),
		array(
			'name' => 'matt_client',
			'status' => 'offline',
			'owner' => 'matt'
		),
		array(
			'name' => 'matt_usrp',
			'status' => 'not_deployed',
			'owner' => 'matt'
		)
	);

	private $images = array(
		'base_attack',
		'base_client',
		'base_usrp'
	);

/**
Hypervisor Getters
*/

	public function get_machines_for_user($user){
		
		$ret = array();

		foreach($this->machines as $machine){
			if($machine['owner'] == $user){
				array_push($ret, $machine);
			}
		}

		return $ret;
	}

	public function get_base_images(){
		return $this->images;
	}

/**
Hypervisor Actions
*/




}
