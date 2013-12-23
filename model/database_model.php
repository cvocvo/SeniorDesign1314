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

	private function db_create_user($user_name, $user_password, $class_name, $is_admin){

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

		/*$query = "SELECT class_id FROM classes WHERE class_name = '" . $class_name  . "';";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_assoc($result);
		$class_id = $row['class_id'];*/

		$query = "INSERT INTO users
		(user_name, user_hash, user_salt, user_class, user_is_admin)
		VALUES ('" . $user_name . "', '" . $user_hash . "', '" . $user_salt . "',
			(SELECT class_id FROM classes WHERE class_name = '" . $class_name  . "'),
			'" . $is_admin . "');";
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

	public function db_delete_user($user_name){

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

	private function db_create_class($class_name){

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

	private function db_delete_class($class_name){

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

	private function db_delete_users_in_class($class_name){

		$users = $this->list_students_in_class($class_name);

		$success = true;
		$message = '';

		$good = 0;
		$bad = 0;
		foreach ($users as $user_name => $user_data) {
			Logger::log('database_model', 'deleting user ' . $user_name);
			$result = $this->delete_user($user_name);
			$success &= $result['success'];
			$message .= $result['message'];
			if($result['message'] != ''){
				$message .= '\n';
			}
			if($result['success']){
				$good++;
			}
			else{
				$bad++;
			}
		}

		$message .= $good . ' users deleted successfully, ' . $bad . ' could not be deleted';

		return array('success' => $success, 'message' => $message);
	}

	private function db_create_users_in_class($class_name, $user_list){

		$ret = array('success' => True, 'message' => '');

		if(!is_array($user_list)){
			$ret['success'] = False;
			$ret['message'] = 'User list not an array';
			return $ret;
		}

		$good = 0;
		$bad = 0;
		foreach($user_list as $user){
			Logger::log('database_model', 'creating user ' . $user);
			$pass = strrev($user);
			$is_admin = false;
			$result = $this->create_user($user, $pass, $class_name, $is_admin);
			$ret['success'] &= $result['success'];
			$ret['message'] .= $result['message'];
			//if something was added, add a newline
			if($result['message'] != ''){
				$ret['message'] .= '\n';
			}
			if($result['success']){
				$good++;
			}
			else{
				$bad++;
			}
		}

		$ret['message'] .= $good . ' users created successfully, ' . $bad . ' errors creating users';

		return $ret;

	}

	/**
	VM State management
	*/

	public function list_vm_types(){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = "SELECT vm_type_name
		FROM vm_types;";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				array_push($ret, $row['vm_type_name']);
			}
		}

		mysqli_close($con);

		return $ret;
	}

	private function db_add_vm_type_to_class($class, $vm_type){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$class = mysqli_escape_string($con, $class);
		$vm_type = mysqli_escape_string($con, $vm_type);

		$query = "INSERT INTO class_vm_types
		VALUES
			((SELECT class_id
				FROM classes
				WHERE class_name = '" . $class . "'),
			(SELECT vm_type_id
				FROM vm_types
				WHERE vm_type_name = '" . $vm_type . "')
		);";
		$result = mysqli_query($con, $query);

		if(!$result){
			//Logger::log("database_model", mysqli_error($con));
			$ret['success'] = False;
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = true;
		}
		
		mysqli_close($con);

		return $ret;
	}

	public function list_vm_types_for_class($class){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$class = mysqli_escape_string($con, $class);

		$query = "SELECT vm_type_name
		FROM vm_types
		JOIN class_vm_types
		ON vm_types.vm_type_id = class_vm_types.vm_type_id
		WHERE class_id =
			(SELECT class_id
				FROM classes
				WHERE class_name = '" . $class . "'
			);";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				array_push($ret, $row['vm_type_name']);
				Logger::log('database_model', $row['vm_type_name']);
			}
		}

		mysqli_close($con);

		return $ret;		
	}

	private function db_delete_vm_types_from_class($class){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$class = mysqli_escape_string($con, $class);

		$query = "DELETE FROM class_vm_types
		 WHERE class_id = (
		 	SELECT class_id
		 	FROM classes
		 	WHERE class_name = '" . $class . "');";
		$result = mysqli_query($con, $query);

		if(!$result){
			//Logger::log("database_model", mysqli_error($con));
			$ret['success'] = False;
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = true;
		}

		mysqli_close($con);

		return $ret;
	}

	private function db_create_vm($vm_name, $vm_type, $vm_owner){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$vm_name = mysqli_escape_string($con, $vm_name);
		$vm_type = mysqli_escape_string($con, $vm_type);
		$vm_owner = mysqli_escape_string($con, $vm_owner);

		/*
INSERT INTO vms (vm_name, vm_type, vm_state, vm_owner) VALUES ('testin', (SELECT vm_type_id FROM vm_types WHERE vm_type_name = 'client'), 'not_deployed', (SELECT user_id FROM users WHERE user_name = 'george'));

		*/


		$query = "INSERT INTO vms (vm_name, vm_type, vm_state, vm_owner)
		VALUES ('" . $vm_name . "',
			(SELECT vm_type_id
				FROM vm_types
				WHERE vm_type_name = '" . $vm_type . "'),
			'not_deployed',
			(SELECT user_id
				FROM users
				WHERE user_name = '" . $vm_owner . "'));";
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

	private function db_delete_user_vms($user){
		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		//SELECT vm_type_name FROM vm_types JOIN class_vm_types ON vm_types.vm_type_id = class_vm_types.vm_type_id WHERE class_id = (SELECT user_class FROM users WHERE user_name = '" . $user . "') AND vm_type_static = 0;

		$user = mysqli_escape_string($con, $user);

		$query = 
		"
		DELETE FROM vms
		WHERE vm_owner =
		(SELECT user_id
			FROM users
			WHERE user_name = '" . $user . "');
		";
		$result = mysqli_query($con, $query);

		if(!$result){
			$ret['success'] = False;
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = True;
			$ret['message'] = '';
		}

		mysqli_close($con);

		return $ret;	
	}

	/**
	creates slots for dynamic vms for user
	*/
	private function db_make_vms_for_user($user){

		$ret = array('success' => True, 'message' => '');

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		//SELECT vm_type_name FROM vm_types JOIN class_vm_types ON vm_types.vm_type_id = class_vm_types.vm_type_id WHERE class_id = (SELECT user_class FROM users WHERE user_name = '" . $user . "') AND vm_type_static = 0;

		$user = mysqli_escape_string($con, $user);

		$query = "SELECT vm_type_name
		FROM vm_types
		JOIN class_vm_types
		ON vm_types.vm_type_id = class_vm_types.vm_type_id
		WHERE class_id =
			(SELECT user_class
				FROM users
				WHERE user_name = '" . $user . "')
		AND vm_type_static = 0;";
		$queryres = mysqli_query($con, $query);

		if(!$queryres){
			$ret['success'] = false;
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($queryres)){
				$dynamic_image = $row['vm_type_name'];
				$vm_name = $user . "_" . $dynamic_image;
				$result = $this->db_create_vm($vm_name, $dynamic_image, $user);
				$ret['success'] &= $result['success'];
				$ret['message'] .= $result['message'];
				if($result['message'] != ''){
					$ret['message'] .= '\n';
				}
			}
		}

		mysqli_close($con);

		return $ret;	
		
	}

	public function create_user($user, $pass, $class, $admin){
		//create user
		$ret = $this->db_create_user($user, $pass, $class, $admin);

		//create vms for user
		if($ret['success']){
			$result = $this->db_make_vms_for_user($user);
			$ret['success'] &= $result['success'];
			$ret['message'] .= $result['message'];
			$ret['message'] .= ($result['message'] != '') ? '\n' : '';
		}

		return $ret;
	}

	public function delete_user($user){
		//delete vms for user
		$ret = $this->db_delete_user_vms($user);

		//delete user
		if($ret['success']){
			$result = $this->db_delete_user($user);
			$ret['success'] &= $result['success'];
			$ret['message'] .= $result['message'];
			$ret['message'] .= ($result['message'] != '') ? '\n' : '';
		}

		return $ret;
	}

	public function create_class($class, $vm_types, $user_list){
		//create class
		$ret = $this->db_create_class($class);

		
		if($ret['success']){
			//create vm_type associations
			foreach ($vm_types as $type) {
				$result = $this->db_add_vm_type_to_class($class, $type);
				$ret['success'] &= $result['success'];
				$ret['message'] .= $result['message'];
				$ret['message'] .= ($result['message'] != '') ? '\n' : '';
			}

			//create user lists
			$result = $this->db_create_users_in_class($class, $user_list);
			$ret['success'] &= $result['success'];
			$ret['message'] .= $result['message'];
			$ret['message'] .= ($result['message'] != '') ? '\n' : '';
		}

		return $ret;
	}

	public function delete_class($class){
		//delete users in class
		$ret = $this->db_delete_users_in_class($class);

		//delete vm type associations
		$result = $this->db_delete_vm_types_from_class($class);
		$ret['success'] &= $result['success'];

		//delete class
		$result = $this->db_delete_class($class);
		$ret['success'] &= $result['success'];

		return $ret;
	}

	public function update_class($class_name, $vm_types){

		//delete vm type associations
		$ret = $this->db_delete_vm_types_from_class($class_name);

		//create vm type associations
		foreach ($vm_types as $type) {
			$result = $this->db_add_vm_type_to_class($class_name, $type);
			$ret['success'] &= $result['success'];
		}

		return $ret;
	}

	private function db_list_static_vms_for_user($user){

		return array();
		
		$ret = array('success' => True, 'message' => '');

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}


		$user = mysqli_escape_string($con, $user);

		$query = "SELECT vm_type_name
		FROM vm_types
		JOIN class_vm_types
		ON vm_types.vm_type_id = class_vm_types.vm_type_id
		WHERE class_id =
			(SELECT user_class
				FROM users
				WHERE user_name = '" . $user . "')
		AND vm_type_static = 0;";
		$queryres = mysqli_query($con, $query);

		if(!$queryres){
			$ret['success'] = false;
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($queryres)){
				$dynamic_image = $row['vm_type_name'];
				$vm_name = $user . "_" . $dynamic_image;
				$result = $this->db_create_vm($vm_name, $dynamic_image, $user);
				$ret['success'] &= $result['success'];
				$ret['message'] .= $result['message'];
				if($result['message'] != ''){
					$ret['message'] .= '\n';
				}
			}
		}

		mysqli_close($con);

		return $ret;
	}

	public function list_vms_for_user($user){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$user = mysqli_escape_string($con, $user);

		$query = "SELECT vm_name, vm_state, vm_expires, vm_port
		FROM vms
		WHERE vm_owner =
		(SELECT user_id
			FROM users
			WHERE user_name = '" . $user . "');";
		$queryres = mysqli_query($con, $query);

		if(!$queryres){
			//$ret['success'] = false;
			//$ret['message'] = mysqli_error($con);
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{

			/*
			'name' => 'matt_attack',
			'status' => 'online',
			'time_remaining' => '16:16:16',
			'address' => '1.2.3.4',
			'owner' => 'matt'
			*/

			$ret = array();
			while($row = mysqli_fetch_assoc($queryres)){
				array_push($ret, $row);
				/*$vm_name = $row['vm_name'];
				$vm_state = $row['vm_state'];
				$vm_expires = $row['vm_expires'];

				$result = $this->db_create_vm($vm_name, $dynamic_image, $user);
				$ret['success'] &= $result['success'];
				$ret['message'] .= $result['message'];
				if($result['message'] != ''){
					$ret['message'] .= '\n';
				}*/
			}
		}

		mysqli_close($con);

		return $ret;	

	}

	public function list_used_ports(){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = "SELECT vm_port FROM vms WHERE vm_port IS NOT NULL";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				array_push($ret, $row['vm_port']);
			}
		}

		mysqli_close($con);

		return $ret;	 
	}

	public function vm_set_port($vm_name, $port){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$vm_name = mysqli_escape_string($con, $vm_name);
		$port = mysqli_escape_string($con, $port);

		Logger::log('database_model', $vm_name . ' ' . $port);

		$query = "UPDATE vms SET vm_port = " . $port . " WHERE vm_name = '" . $vm_name . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			//Logger::log("database_model", mysqli_error($con));
			$ret['success'] = False;
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = true;
		}
		
		mysqli_close($con);

		return $ret;
	}

	public function vm_set_state($vm_name, $vm_state){

		$ret = array('success' => False, 'message' => '');

		$con = $this->connect();
		if(!$con){
			$ret['message'] = mysqli_connect_error();
			return $ret;
		}

		$vm_name = mysqli_escape_string($con, $vm_name);
		$vm_state = mysqli_escape_string($con, $vm_state);

		$query = "UPDATE vms SET vm_state = '" . $vm_state . "' WHERE vm_name = '" . $vm_name . "';";
		$result = mysqli_query($con, $query);

		if(!$result){
			//Logger::log("database_model", mysqli_error($con));
			$ret['success'] = False;
			$ret['message'] = mysqli_error($con);
		}
		
		else{
			$ret['success'] = true;
		}
		
		mysqli_close($con);

		return $ret;
	}

	public function list_users(){

		$con = $this->connect();
		if(!$con){
			return $this->report_error();
		}

		$query = "SELECT user_name
		FROM users;";
		$result = mysqli_query($con, $query);

		$ret = array();

		if(!$result){
			Logger::log("database_model", mysqli_error($con));
		}
		
		else{
			while($row = mysqli_fetch_assoc($result)){
				array_push($ret, $row['user_name']);
			}
		}

		mysqli_close($con);

		return $ret;

	}
}

?>
