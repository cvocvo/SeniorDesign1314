<?php

class Database_Model{

	public function __construct(){}

	public function authenticate($user, $pass){
		return $this->users[$user]['password'] == $pass;
	}

	public function is_admin($user){
		return $this->users[$user]['is_admin'];
	}

	private $users = array(
		'george' => array(
			'password' => 'ee201',
			'is_admin' => True
		),
		'matt' => array(
			'password' => 'lul',
			'is_admin' => False
		)
	);



}

?>
