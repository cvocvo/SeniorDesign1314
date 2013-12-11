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

	public function create_user($user_name, $user_password, $class_name, $is_admin){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$user_name = mysqli_escape_string($con, $user_name);
		$user_password = mysqli_escape_string($con, $user_password);
		$class_name = mysqli_escape_string($con, $class_name);
		$is_admin = mysqli_escape_string($con, $is_admin);

		$user_salt = $this->new_salt();

		$user_hash = $this->get_password_hash($user_password, $user_salt);

		$query = "SELECT class_id FROM classes WHERE class_name = '" . $class_name  . "';";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_assoc($result);
		$class_id = $row['class_id'];

		$query = "INSERT INTO users
		(user_name, user_hash, user_salt, user_class, user_is_admin)
		VALUES ('" . $user_name . "', '" . $user_hash . "', '" . $user_salt . "',
			'" . $class_id . "', '" . $is_admin . "');";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
		}

		mysqli_close($con);

		return $ret;
	}

	public function update_user($user_name, $user_password, $class_name){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$user_name = mysqli_escape_string($con, $user_name);
		$user_password = mysqli_escape_string($con, $user_password);
		$class_name = mysqli_escape_string($con, $class_name);

		$user_salt = '';
		$user_hash = '';

		if($user_password != ''){
			
			$user_salt = $this->new_salt();
			$user_hash = $this->get_password_hash($user_password, $user_salt);
		}

		$class_id = '';

		if($class_name != ''){

			$query = "SELECT class_id FROM classes WHERE class_name = '" . $class_name  . "';";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_assoc($result);
			$class_id = $row['class_id'];
		}

		$query = '';
		if($user_hash != '' && $class_id != ''){
			
			$query = "UPDATE users
			SET user_hash = '" . $user_hash . "',
			user_salt = '" . $user_salt . "',
			user_class = '" . $class_id . "'
			WHERE user_name = '" . $user_name . "';";
		}
		elseif($user_hash != '' && $class_id == ''){
			
			$query = "UPDATE users
			SET user_hash = '" . $user_hash . "',
			user_salt = '" . $user_salt . "'
			WHERE user_name = '" . $user_name . "';";
		}
		else{
			
			$query = "UPDATE users
			SET user_class = '" . $class_id . "'
			WHERE user_name = '" . $user_name . "';";
		}

		Logger::log("database_model", $query);

		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
		}

		mysqli_close($con);

		return $ret;
	}

	public function promote_user_to_admin($user){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$user = mysqli_escape_string($con, $user);

		$query = "UPDATE users SET user_is_admin = true WHERE user_name = '" . $user . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
		}

		mysqli_close($con);

		return $ret;

	}

	public function demote_admin($user){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$user = mysqli_escape_string($con, $user);

		$query = "UPDATE users SET user_is_admin = false WHERE user_name = '" . $user . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
		}

		mysqli_close($con);

		return $ret;
	}

	public function delete_user($user_name){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$user_name = mysqli_escape_string($con, $user_name);

		$query = "DELETE FROM users WHERE user_name = '" . $user_name . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
		}

		mysqli_close($con);

		return $ret;
	}

	public function create_class($class_name){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$class_name = mysqli_escape_string($con, $class_name);

		$query = "INSERT INTO classes (class_name)
		VALUES ('" . $class_name . "');";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
		}

		mysqli_close($con);

		return $ret;
	}

	public function update_class($class_name){

	}

	public function delete_class($class_name){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$class_name = mysqli_escape_string($con, $class_name);

		$query = "DELETE FROM classes WHERE class_name = '" . $class_name . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
		}

		mysqli_close($con);

		return $ret;
	}

	public function delete_users_in_class($class_name){

		$users = $this->list_students_in_class($class_name);

		$success = true;
		$message = '';

		foreach ($users as $user_name => $user_data) {
			Logger::log('database_model', 'deleting user ' . $user_name);
			$result = $this->delete_user($user);
			$success &= $result['success'];
			$message .= ($result['message'] != '') ? $user_name . ': ' . $result['message'] . '\n' : '';
		}

		return array('success' => $success, 'message' => $message);
	}

	public function create_users_in_class($class_name, $user_list){

	}
}

?>
