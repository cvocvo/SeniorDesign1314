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
		'EE201B',
		'default'
	);

/**
Database Queries
*/

	public function list_classes(){
		return $this->classes;
	}

	public function is_class($class){
		return in_array($class, $this->classes);	
	}
	
	public function list_students_in_class($class){
		$ret = array();

		foreach($this->users as $name=>$data){
			if(array_key_exists('class', $data) && 
					$this->users[$name]['class'] == $class){
				$ret[$name] = $data;
			}
		}
		return $ret;
	}

	public function is_user($user){
		return array_key_exists($user, $this->users);
	}

	public function get_class_for_user($user){
		if(array_key_exists($user, $this->users)
				&& isset($this->users[$user]['class'])){
			return $this->users[$user]['class'];
		}
		return 'default';
	}

	public function list_admins(){
		$ret = array();

		foreach($this->users as $name=>$data){
			if(array_key_exists('is_admin', $data) &&
					$this->users[$name]['is_admin']){
				$ret[$name] = $data;
			}
		}
		return $ret;
	}

	public function list_nonadmins(){
		$ret = array();

		foreach($this->users as $name => $data){
			if(array_key_exists('is_admin', $data) &&
					!$this->users[$name]['is_admin']){
				$ret[$name] = $data;
			}
		}
		return $ret;
	}

/**
Database Actions
*/

}

?>
