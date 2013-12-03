<?php

/**
Model of the hypervisor

Abstracts hypervisor interaction into easy to use functions.
Implements the functionality needed to interact with
VMWare ESXi 5.1 using the VIX command line toolkit.
*/

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
		),
		array(
			'name' => 'tahsin_attack',
			'status' => 'not_deployed',
			'owner' => 'tahsin'
		),
		array(
			'name' => 'tahsin_client',
			'status' => 'not_deployed',
			'owner' => 'tahsin'
		),
		array(
			'name' => 'tahsin_usrp',
			'status' => 'not_deployed',
			'owner' => 'tahsin'
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
