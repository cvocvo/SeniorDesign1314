
<?php 
	class DB_Backend
	{
		function __construct()
		{
			include_once "/usr/share/pear/wseclab.php";
			$conf = new config();

			$this->_host = $conf->MYSQL_HOSTNAME;
			$this->_db = $conf->MYSQL_DATABASE;
			$this->_user_table = $conf->MYSQL_TABLE;
			$this->_user = $conf->MYSQL_USERNAME;
			$this->_pass = $conf->MYSQL_PASSWORD;
			$this->_port_table = $conf->MYSQL_PORT_TABLE;
		}

		/********************************************************************************************************
		*													*
		********************************************************************************************************/
		public function add_user($uname, $upass)
		{
			if($this->has_symbols($uname))# or $this->has_symbols($upass))
				return "ERROR: Username cannot contain symbols";
			if(strlen($uname) < 1)
				return "ERROR: Username cannot be blank";
			if(strlen($uname) > 16)
				return "ERROR: Username cannot be longer than 16 characters";
			if(strlen($upass) < 1)
				return "ERROR: password cannot be blank";
			
				
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connet...\n");
			mysql_select_db($this->_db)or die("Could not select database...\n");

			$q = "INSERT INTO $this->_user_table (uname, upass) VALUES ('$uname', SHA1('$upass'))";
			
			if (!mysql_query($q, $con))
  				return mysql_error();

			mysql_close($con);
			return 0;
		}
		/********************************************************************************************************
		*													*
		********************************************************************************************************/
		public function remove_user($uname)
		{
			if($this->has_symbols($uname))
				return "Username should not contain non alpha-numeric characters";
				
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connet...\n");
			mysql_select_db($this->_db)or die("Could not select database...\n");

			$q1 = "DELETE FROM $this->_user_table WHERE uname='$uname'";
			$q2 = "DELETE FROM $this->_port_table WHERE uname='$uname'";
			
			if (!mysql_query($q1, $con))
  				return mysql_error();

			if(!mysql_query($q2, $con))
				return mysql_error();

			mysql_close($con);
			return 0;
		}

		/********************************************************************************************************
		*													*
		********************************************************************************************************/
		public function auth_user($uname, $upass)
		{
			#if($this->has_symbols($uname) or $this->has_symbols($upass))
			#	return "ERROR: Username and password must be alpha-numeric";
			if(strlen($uname) < 1)
				return "ERROR: Username cannot be blank";
			if(strlen($upass) < 1)
				return "ERROR: password cannot be blank";
					
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database.../n");
			
			$q = "SELECT uid, lastlogin FROM $this->_user_table WHERE uname='$uname' and upass=SHA1('$upass')";
			
			$respons = mysql_query($q);

			$count = mysql_num_rows($respons);
						
			$row = mysql_fetch_array($respons);

			if($count > 1)
			{
				mysql_close($con);
				return "ERROR: More than one user for given username in database. Contact your administrator.";
			}
			else if($count < 1)
			{
				mysql_close($con);
				return "ERROR: Username or password incorrect";
			}

			if($row[1] != null)
			{
				$q = "UPDATE $this->_user_table SET lastlogin=now() WHERE uid=$row[0]";
				$response = mysql_query($q);
			}

			mysql_close($con);

			return $row[0];
		}
		
		public function get_users()
		{
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect... <br />");	
			mysql_select_db($this->_db)or die("Could not select database... <br />");
		
			$q = "SELECT uname, type FROM $this->_user_table";
			
			$response = mysql_query($q);

			if(!$response)
			{
				mysql_close($con);
				return mysql_error();
			}

			$retval = array();
			while($row = mysql_fetch_array($response, MYSQL_NUM))
			{
				$retval[$row[0]] =  $row[1];
			}
			
			mysql_close($con);		
			return $retval;

		}
		/********************************************************************************************************
		*													*
		********************************************************************************************************/
		public function change_pass($uid, $upass)
		{

			if($this->has_symbols($uid))# or $this->has_symbols($upass))
				return "ERROR: Username must be alpha-numeric";
			if(strlen($upass) < 1)
				return "ERROR: Password cannot be blank";

			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect... <br />");	
			mysql_select_db($this->_db)or die("Could not select database... <br />");
		
			$q = "UPDATE $this->_user_table SET upass = SHA1('$upass') WHERE uid = '$uid'";
			
			$q2 = "UPDATE $this->_user_table SET lastlogin = now() WHERE uid = '$uid'";

			if(!mysql_query($q))
			{
				mysql_close($con);
				return mysql_error();
			}
			if(mysql_query($q2))
			{
				mysql_close($con);
				return mysql_error();
			}
			
			mysql_close($con);		
			return 0;
		}
		
		/********************************************************************************************************
		*													*
		********************************************************************************************************/
		public function has_symbols($str)
		{
			if(ereg("[^A-Za-z0-9]", $str, $empty))
				return true;
	
			return false;
		}

		/********************************************************************************************************
		*													*
		********************************************************************************************************/
		public function strip_symbols($str){ return ereg_replace("[^A-Za-z0-9]", "", $str); }

		/********************************************************************************************************
		*													*
		*********************************************************************************************************/
		public function get_session_info($id)
		{
			if(!is_numeric($id))
				return null;
	
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database.../n");
	
			$q = "SELECT * FROM $this->_user_table WHERE uid='$id'";
		
			$response = mysql_query($q);

			$count = mysql_num_rows($response);
		
			if($count != 1)
				return null;

			$retval = mysql_fetch_array($response);
			mysql_close($con);


			return $retval;	
		}

		public function clear_user_table()
		{
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database...\n");

			$q = "DELETE FROM $this->_user_table WHERE type != 'admin'";

			$response = mysql_query($q);
			
			if(!$response)
			{
				mysql_close($con);
				return mysql_error();
			}
			mysql_close($con);
			return 0;
		}

		public function lastsession($uname)
		{
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database.../n");
	
			$q = "SELECT lastsession FROM $this->_user_table WHERE uname='$uname'";
		
			$response = mysql_query($q);

			$count = mysql_num_rows($response);
		
			if($count != 1)
				return null;

			$retval = mysql_fetch_array($response);
			mysql_close($con);

			return $retval[0];
		}

		public function newsession($uname)
		{
			
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database.../n");

			$q = "UPDATE $this->_user_table SET lastsession=now() WHERE uname='$uname'";
			$response = mysql_query($q);
			mysql_close($con);

			return "Session Updated";
		}

		public function add_portdef($user, $aport, $cport)
		{

			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database...\n");

			$q = "SELECT * FROM ".$this->_port_table." WHERE uname='$user'";
			$response = mysql_query($q);

			$count = mysql_num_rows($response);

			if($count > 0)
				return "ERROR: User already exists";

			$q = "INSERT INTO $this->_port_table (uname, aport, cport) VALUES ('$user', '$aport', '$cport')";
			
			if (!mysql_query($q, $con))
  				return mysql_error();
			
			mysql_close($con);

			return 0;
		}
	
		public function get_portdef($user)
		{
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database...\n");

			$q = "SELECT aport, cport FROM $this->_port_table WHERE uname='$user'";
			$response = mysql_query($q);

			$row = mysql_fetch_array($response);

			$retval['aport'] = $row[0];
			$retval['cport'] = $row[1];
			
			mysql_close($con);

			return $retval;
		}
		public function get_last_portdef()
		{
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database...\n");

			$q = "SELECT aport, cport FROM $this->_port_table ORDER BY cport DESC";
			$response = mysql_query($q);

			if(mysql_num_rows($response) == 0)
				return array();

			$row = mysql_fetch_array($response);

			$retval['aport'] = $row[0];
			$retval['cport'] = $row[1];
			
			mysql_close($con);

			return $retval;
		}

		public function user_exists($uname)
		{
			$con = mysql_connect($this->_host, $this->_user, $this->_pass)or die("Could not connect...\n");
			mysql_select_db($this->_db)or die("Could not select database...\n");

			$q = "SELECT * FROM $this->_user_table WHERE uname='$uname'";
			$response = mysql_query($q);

			$count = mysql_num_rows($response);
				
			mysql_close($con);
				
			if($count > 0)
				return true;

			return false;

			return $retval;
		}
	}
?>
