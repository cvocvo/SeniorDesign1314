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
			'class'    => 'CprE530A'
		),
		'tahsin' => array(
			'password' => 'labview',
			'is_admin' => False,
			'class' => 'CprE530B'
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

	public function is_class($class){
		return in_array($class, $this->classes);	
	}
	
	public function list_students_in_class($class){
		/*$ret = array();

		foreach($this->users as $user){
			if($user->class == $class){
				array_push($ret, $user);
			}
		}*/
		return $this->users;
	}

	public function is_user($user){
		return in_array($user, $this->users);
	}



}

?>
