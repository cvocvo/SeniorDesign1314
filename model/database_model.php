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

	private function new_salt(){
		return bin2hex(openssl_random_pseudo_bytes(32));
	}

	private function get_password_hash($pass, $salt){
		return hash("sha256", $pass . $salt);
	}

/**
Database Queries
*/

	public function authenticate($user, $pass){
		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$user = mysqli_escape_string($con, $user);
		$pass = mysqli_escape_string($con, $pass);

		$query = " SELECT user_hash, user_salt
		FROM users
		WHERE user_name = '" . $user . "';";
		$result = mysqli_query($con, $query);

		$ret = false;

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}

		elseif(mysqli_num_rows($result)){
			$row = mysqli_fetch_assoc($result);
			Logger::log("database_model", $user . ' challenge: '
				. $row['user_salt'] . ' ' . $row['user_hash']);
			$ret = $this->get_password_hash($pass, $row['user_salt'])
				== $row['user_hash'];
		}

		mysqli_close($con);

		return $ret;
	}

	public function is_admin($user){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$user = mysqli_escape_string($con, $user);

		$query = " SELECT user_is_admin
		FROM users
		WHERE user_name = '" . $user . "';";
		$result = mysqli_query($con, $query);

		$ret = false;

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		elseif(mysqli_num_rows($result)){
			$row = mysqli_fetch_assoc($result);
			$ret = $row['user_is_admin'];
		}

		mysqli_close($con);

		return $ret;
	}

	public function list_classes(){
		
		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = " SELECT class_name
		FROM classes;";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				array_push($ret, $row['class_name']);
			}
		}

		mysqli_close($con);

		return $ret;
	}

	public function is_class($class){
		
		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$class = mysqli_escape_string($con, $class);

		$query = " SELECT class_name
		FROM classes
		WHERE class_name = '" . $class . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			$ret = mysqli_num_rows($result) > 0;
		}

		mysqli_close($con);

		return $ret;
	}
	
	public function list_students_in_class($class){
		
		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$class = mysqli_escape_string($con, $class);

		$query = "SELECT user_name, user_is_admin, class_name
		FROM users JOIN classes
		ON users.user_class = classes.class_id
		AND classes.class_name = '" . $class . "';";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				$data = array();
				$data['is_admin'] = $row['user_is_admin'];
				$data['class'] = $row['class_name'];
				$ret[$row['user_name']] = $data;
			}
		}

		mysqli_close($con);

		return $ret;
	}

	public function is_user($user){

		return true;
		
		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$user = mysqli_escape_string($con, $user);

		$query = "SELECT user_name FROM users WHERE user_name = '" . $user . "';";

		$result = mysqli_query($con, $query);

		$ret = false;

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		else{
			$ret = mysqli_num_rows($result) > 0;
		}

		mysqli_close($con);

		return $ret;
	}

	public function get_class_for_user($user){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$user = mysqli_escape_string($con, $user);

		$query = "SELECT classes.class_name
		FROM users JOIN classes
		ON users.user_class = classes.class_id
		AND users.user_name = '" . $user . "';";
		$result = mysqli_query($con, $query);

		$ret = NULL;

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		elseif (mysqli_num_rows($result)) {
			$row = mysqli_fetch_assoc($result);
			$ret = $row['class_name'];
		}

		mysqli_close($con);

		return $ret;
	}

	public function list_admins(){
		
		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = "SELECT user_name, user_is_admin, class_name
		FROM users JOIN classes
		ON users.user_class = classes.class_id
		AND users.user_is_admin = true;";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				$data = array();
				$data['is_admin'] = $row['user_is_admin'];
				$data['class'] = $row['class_name'];
				$ret[$row['user_name']] = $data;
			}
		}

		mysqli_close($con);

		return $ret;
	}

	public function list_nonadmins(){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = "SELECT user_name, user_is_admin, class_name
		FROM users JOIN classes
		ON users.user_class = classes.class_id
		AND users.user_is_admin = false;";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				$data = array();
				$data['is_admin'] = $row['user_is_admin'];
				$data['class'] = $row['class_name'];
				$ret[$row['user_name']] = $data;
			}
		}

		mysqli_close($con);

		return $ret;
	}

/**
Database Actions
*/

}

?>
