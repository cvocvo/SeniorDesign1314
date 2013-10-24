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
			'is_admin' => False,
			'class'    => 'cpre530A'
		)
	);

	private $classes = array(
		'CprE530A',
		'CprE530B',
		'EE201A',
		'EE201B'
	);

	public function list_classes(){
		return $this->classes;
	}

	public function list_students_in_class($class){
		$ret = array();

		foreach($this->users as $user){
			if($user->class == $class){
				array_push($ret, $user);
			}
		}
	}



}

?>
