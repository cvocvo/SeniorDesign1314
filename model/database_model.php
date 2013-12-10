<?php

include_once(SERVER_ROOT . '/util/logger.php');

/**
Model of the database

Abstracts database queries into easier to understand
function calls. Implements all functionality needed
to interact with a MySQL database.
*/

class Database_Model{

	public function __construct(){}

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
			'is_admin' => True,
			'class' => 'CprE530A'
		)
	);

	private $classes = array(
		'CprE530A',
		'CprE530B',
		'EE201A',
		'EE201B',
		'default'
	);

	private function connect(){
		$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if(mysqli_connect_errno()){
			return NULL;
		}
		return $con;
	}

	private function report_error(){
		return array(
			'error' => TRUE,
			'message' => mysqli_connect_error()
		);
	}

/**
Database Queries
*/

	public function authenticate($user, $pass){
		/*$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = " SELECT user_hash, user_salt
		FROM users
		WHERE user_name = '" . $user . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}

		while($row = mysqli_fetch_array($result)){
			Logger::log("database_model", $row['user_hash'] . ' ' . $row['user_salt']);
		}

		mysqli_close($con);*/

		return $this->users[$user]['password'] == $pass;
	}

	public function is_admin($user){
		return $this->users[$user]['is_admin'];
		/*$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = " SELECT user_is_admin
		FROM users
		WHERE user_name = '" . $user . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}

		while($row = mysqli_fetch_array($result)){
			Logger::log("database_model", $row['user_hash'] . ' ' . $row['user_salt']);
		}

		mysqli_close($con);*/
	}

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
