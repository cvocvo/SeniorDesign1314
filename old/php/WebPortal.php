<?php 
	ini_set('display_errors', '1');
	
	/****************************************************************
	*	Class: WebPortal											*
	*	Function:	Used by webpages for getting required 			*
	*				information. 									*
	*	Methods:	login											*
	*				logout											*
	*				check_radios									*
	*				check_equipment									*
	*				backup_machine									*
	*				reimage											*
	*				boot_img										*
	*				stop_img										*
	*																*	
	****************************************************************/
	class WebPortal
	{

		function __construct()
		{
			include_once "/usr/share/pear/wseclab.php";
			$conf = new config();
			include_once ($conf->PHP_PATH."db_backend.php");
			include_once ($conf->PHP_PATH."hypervisor_com.php");

			$this->db = new db_backend();
			#$this->hypev = new hypervisor_com();

			$this->logging = $conf->ENABLE_LOGGING;
			$this->logfile = "/var/log/wseclab.d/webportal.log";
			$this->webhome = $conf->WEB_HOME;
			$this->webuser = $conf->WEB_USER;
			$this->phppath = $conf->PHP_PATH;
		}
		/************************************************************
		*	Function called by webpage to vailidate a user. If 		*
		*	login is successful return value is true and the php	*
		*	$_SESSION values are set.								*
		*															*
		*	$user 		- String representing username				*
		*	$password	- String representing users password		*
		*															*
		*	Return 		- True if successful otherwise false.		*
		************************************************************/
		public function login($user, $password)
		{	
			// If auth_user returns a value user's credentials are valid.
			$uid = $this->db->auth_user($user, $password);
			echo $this->db->auth_user($user, $password);
			
			if(is_numeric($uid))
			{
				// Get informations for a user session from the database.
				$session_info = $this->db->get_session_info($uid);
				// Set the php session values.
				$_SESSION['user'] = $session_info['uname'];
				$_SESSION['id'] = $session_info['uid'];
				$_SESSION['lastlogin'] = $session_info['lastlogin'];
				if($_SESSION['lastlogin'] == null)
					$_SESSION['type'] = "firstlogin";
				else
					$_SESSION['type'] = $session_info['type'];
				return 0;
			}
			return $uid;
		}
	
		/************************************************************
		*	Function called by webpage when user logs out. It 		*
		*	destroys the current php session for a user.			*
		************************************************************/
		public function logout()
		{
			session_destroy();
			session_unset();
			session_write_close();
		}

		/************************************************************
		*	Function called by webpage when user logs out. It 		*
		*	destroys the current php session for a user.			*
		************************************************************/
		public function change_password($new, $confirm)
		{
			if(!$this->is_valid_session())
			{
				$this->write_log("Attempted password change without valid session");
				die();
			}
			if(strcmp($new, $confirm))
			{
				return "Failed to confirm passwords.  Passwords do not match";
			}
			
			$error = $this->db->change_pass($_SESSION['id'], $new);
			
			if($error)
			{
				return $error;
			}

			$info = $this->db->get_session_info($_SESSION['id']);

			$_SESSION['type'] = $info['type'];
			
			return 0;
			
		}
		
		/************************************************************
		*	Function called by webpage to check on the status of 	*
		*	all the radios.	Does not require a valid user session.	*
		*															*	
		*	Return	- Array of booleans.  True if radio is in use.	*
		************************************************************/
		public function check_radios()
		{
			$response = $this->hypev->get_radio_states();
			$arr = explode(" ", $response);
			$radios = array();
			foreach($arr as $value)
			{
				$info = explode(":", $value);
				$num_total = explode("/", $info[1]);
				$num = $num_total[0];
				switch($info[0])
				{
					case 'w':
					case 'W':
						$radios['wifi'] = $num;
						break;
					case 'r':
					case 'R':
						$radios['rfid'] = $num;
						break;
					case 'u':
					case 'U':
						$radios['usrp'] = $num;
						break;
					case 'b':
					case 'B':
						$radios['bluetooth'] = $num;
						break;
				}
			}
			return $radios;
		}

		/************************************************************
		*	Function called by webpage to check the status of a 	*
		*	user's virtual machines. Requires a valid user session.	*
		*															*
		*	Return	- Key value pair array. 						*
		*															*
		*				Value	Meaning								*
		*				  0 	machine is off						*
		*				  1 	machine is on / no radio			*
		*				  2		machine is on / using a radio		*
		************************************************************/
		public function check_equipment()
		{
			if(!$this->is_valid_session())
				return null;
			
			$machine['attack'] = $this->hypev->machine_state($_SESSION['user'], "attack");
			$machine['client'] = $this->hypev->machine_state($_SESSION['user'], "client");

			return $machine;
		}
		
		/************************************************************
		*	Function called by webpage for backing up a virtual 	*
		*	machine.  Requires a valid users session and that user	*
		*	owns the given machine.									*
		*															*
		*	Return	- True if successful, otherwise false.			*
		************************************************************/
		//TODO test
		public function clone_img($type, $action)
		{
			if(!$this->is_valid_session())
			{	
				$this->write_log("Attempted Backup/Reimage/Restore Without Valid Session");
				return 1;
			}
			if($action == "reimage")
			{

				$index = 0;
                        	$fd = fopen($this->webhome."userlist.txt", "w");
                        	$str = $_SESSION['user']." password\n";
                        	fwrite($fd, $str);
                        	fclose($fd);					
	
				$output = array();
        	                exec("crontab -l  > ".$this->webhome."crontemp", $output, $status);
               	        	exec("echo '*/5 * * * * ".$this->phppath."configure_machines.php' >> ".$this->webhome."crontemp", $output, $status);
               		        exec("crontab ".$this->webhome."crontemp", $output, $status);
                        	shell_exec("rm ".$this->webhome."crontemp");
		
			}

			return $this->hypev->clone_img($_SESSION['user'], $type, $action);
		}

		/************************************************************
		*	Function called by webpage for booting up a vitual 		*
		*	machine. Requires a valid user session.					*
		*															*
		*	$machine_type	- String values: 'attack' 'client'		*
		*															*
		*	Return	- True if boot was successful.					*
		************************************************************/
		public function boot_env($client_radios, $attack_radios)
		{
			if(!$this->is_valid_session())
			{	
				$this->write_log("Attempted Boot Environment Without Valid Session");
				return 1;
			}
			
			$response['client'] = $this->hypev->boot($_SESSION['user'], "client", $client_radios);
			$response['attack'] = $this->hypev->boot($_SESSION['user'], "attack", $attack_radios);
			
			if(!$response['client'] && !$response['attack'])
			{
				$this->db->newsession($_SESSION['user']);
				return 0;
			}

			//TODO handle errors

			return 1;
		}

		/************************************************************
		*	Function called by webpage for powering down up a 		*
		*	virtual machine. Requires a valid user session.			*
		*															*
		*	$machine_type	- String values: 'attack' 'client'		*
		*															*
		*	Return	- True if power down was successful.			*
		************************************************************/
		public function stop_env()
		{
			if(!$this->is_valid_session())
			{	
				$this->write_log("Attempted Stop Environment Without Valid Session");
				return 1;
			}
			
			$response['attack'] = $this->hypev->power_down($_SESSION['user'], "attack");
			$response['client'] = $this->hypev->power_down($_SESSION['user'], "client");
			if(!$response['attack'] && !$response['client'])
				return 0;
			
			//TODO handle errors

			return 1;
		}

		/************************************************************
		*	Private function for checking if the user has a valid	*
		*	session.												*
		************************************************************/
		public function is_valid_session()
		{
			return isset($_SESSION['id']);
		}
		
		/************************************************************
		*															*
		************************************************************/
		public function is_admin()
		{
			if(strcmp($_SESSION['type'], "admin") == 0)
				return true;
			return false;
		}
		
		public function is_user()
		{
			if(!isset($_SESSION['type']))
				return false;

			if(strcmp($_SESSION['type'], "user") == 0)
				return true;
			return false;
		}

		/************************************************************
		*															*
		************************************************************/
		public function session_type()
		{
			if(!$this->is_valid_session())
				return "none";
			return $_SESSION['type'];
		}

		/************************************************************
		*															*
		************************************************************/
		public function add_user($uname, $upass, $datastore)
		{
			if(!$this->is_admin())
			{
				$this->write_log("Attempted To Add User Without Admin Session");
				return 1;
			}
			$uname = trim($uname, "\n");
			$pass = trim($upass, "\n");

			if(ereg("[^A-Za-z0-9]", $uname, $empty))
			{
				$this->write_log("ERROR: Invalid user: ".$uname." (non alpha-numeric character)");
				return 1;
			}
			if(ereg("[^A-Za-z0-9]", $upass, $empty))
			{
				$this->write_log("ERROR: Invalid user id: ".$upass." (non alpha-numeric character)");
				return 1;
			}
			if(strlen($uname) < 1)
			{
				$this->write_log("ERROR: Username blank");
				return 1;
			}
			if(strlen($uname) > 16)
			{
				$this->write_log("ERROR: Username longer than 16 characters");
				return 1;
			}
			if(strlen($upass) < 1)
			{
				$this->write_log("ERROR: password blank");
				return 1;
			}
			if($this->db->user_exists($uname))
			{
				$this->write_log("ERROR: user already exists");
				return 1;
			}

			$users = array();
			$users[] = $uname;

			$error = $this->hypev->create_class($users, $datastore);

			if($error)
				return 1;

			$index = 0;
			$fd = fopen($this->webhome."userlist.txt", "w");
			$str = $uname." ".$upass."\n";
			fwrite($fd, $str);
			fclose($fd);

			$output = array();


			exec("crontab -l  > ".$this->webhome."crontemp", $output, $status);
			exec("echo '*/5 * * * * ".$this->phppath."configure_machines.php' >> ".$this->webhome."crontemp", $output, $status);
			exec("crontab ".$this->webhome."crontemp", $output, $status);
			shell_exec("rm ".$this->webhome."crontemp");

			//$error = $this->db->add_user($uname, $upass);
			//if($error)
			//	return 1;
			
			return 0;
		}

		/************************************************************
		*															*
		************************************************************/
		public function remove_user($uname)
		{
			if(!$this->is_admin())
			{
				$this->write_log("Attempted to remove user without an admin session");
				return 1;
			}
			
			$error = $this->db->remove_user($uname);
			if($error)
				return 1;
		
			$users = array();
			$users[] = $uname;

			$error = $this->hypev->destroy_class($users);
			if($error)
				return $error;
			
			return 0;
		}

		/************************************************************
		*															*
		************************************************************/
		public function get_states($machine_type)
		{
			if(!$this->is_valid_session())
				return null;

			$users = $this->get_userlist();
			$on = $this->hypev->users_powered_on($machine_type);
			
			$retval = array();
			foreach($users as $key => $value)
			{
				if($value != 'admin')
				{
					$retval[$key] = in_array($key, $on);
				}
			}
			return $retval;
			
		}

		/************************************************************
		*															*
		************************************************************/
		public function get_userlist()
		{
			if(!$this->is_valid_session())
				return null;

			return $this->db->get_users();
		}

		/************************************************************
		*															*
		************************************************************/
		public function get_username()
		{
			if(!$this->is_valid_session())
				return "none";

			return $_SESSION['user'];
		}

		/************************************************************
		*															*
		************************************************************/
		public function admin_powerdown($user, $type)
		{
			if(!$this->is_admin())
			{
				$this->write_log("Attempted admin power down machine without admin session");
			}
			$error = $this->hypev->power_down($user, $type);
			
			if($error)
				return 1;

			return 0;
		}
		/************************************************************
		*															*
		************************************************************/
		public function create_class($filepath, $datastore)
		{
			if(!$this->is_admin())
			{
				$this->write_log("ERROR: Must be an administrator");
				return 1;
			}

			if(!is_file($filepath))
			{
				$this->write_log("ERROR: File '$filepath' does not exist");
				return 1;
			}

			$lines = file($filepath);
			$userlist = array();
			$userlist['user'] = array();
			$userlist['pass'] = array();
			foreach($lines as $key => $line)
			{
				$line = trim($line, "\n\r");
				$user = explode(" ", $line);
				if(count($user) >= 2)
				{
					if(ereg("[^A-Za-z0-9]", $user[0], $empty))
					{
						$this->write_log("ERROR: INvalid user: ".$user[0]." at line: $key (non alpha-numeric character)");
						return 1;
					}
					if(ereg("[^A-Za-z0-9]", $user[1], $empty))
					{
						$this->write_log("ERROR: Invalid user id: ".$user[1]." at line: $key (non alpha-numeric character)");
						return 1;
					}
					if(strlen($user[0]) < 1)
					{
						$this->write_log("ERROR: Username blank at line: $line");
						return 1;
					}
					if(strlen($user[0]) > 16)
					{
						$this->write_log("ERROR: Username longer than 16 characters at line: $line");
						return 1;
					}
					if(strlen($user[1]) < 1)
					{
						$this->write_log("ERROR: password blank at line: $line");
						return 1;
					}
					if(in_array($user[0], $userlist['user']))
					{
						$this->write_log("ERROR: Duplicate user: ".$user[0]." at line: $key");
						return 1;
					}
					if(in_array($user[1], $userlist['pass']))
					{
						$this->write_log("ERROR: Duplicate user id: ".$user[1]." at line $key");
						return 1;
					}

					$userlist['user'][] = $user[0];
				}
			}
			
			$error = 0;//$this->hypev->create_class($userlist['user'], $datastore);

			if($error)
				return 1;

			$index = 0;
			$fd = fopen($this->webhome."userlist.txt", "c");
			for($index = 0; $index < count($userlist['user']); $index++)
			{
				$str = $userlist['user'][$index]." ".$userlist['pass'][$index]."\n";
				fwrite($fd, $str);
			}
			fclose($fd);
			
			shell_exec("crontab -l > ".$this->webhome."crontemp");
			shell_exec("echo '*/5 * * * * ".$this->phppath."configure_machines.php' >> ".$this->webhome."crontemp");
			shell_exec("crontab ".$this->webhome."crontemp");
			shell_exec("rm ".$this->webhome."crontemp");

			//$error = $this->db->add_user($uname, $upass);
			//if($error)
				//return 1;
		
			return 0;
		}
		
		public function get_lastsession()
		{
			if(!$this->is_user())
				return null;
				
			return strtotime($this->db->lastsession($_SESSION['user']));
		}

		public function renew_session()
		{
			
			if(!$this->is_user())
				return null;

			return $this->db->newsession($_SESSION['user']);
		}

		public function get_portdef()
		{
			if(!$this->is_valid_session())
				return null;

			return $this->db->get_portdef($_SESSION['user']);
		}

		public function get_datastores()
		{
			if(!$this->is_admin())
			{
				$this->write_log("Attempted To Add User Without Admin Session");
				return null;
			}
			
			return $this->hypev->get_datastores();
		}

		private function write_log($str)
		{
			if(!$this->logging)
				return;

			$fd = fopen($this->logfile, "a");
			$format = date("j-m-y h:i:s")."\t".$str."\n";
			fwrite($fd, $format);
			fclose($fd);
		}
	}
?>
