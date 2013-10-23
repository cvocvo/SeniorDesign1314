<?php
	
	/************************************************************
	*	Class:		hypervisor_com								*
	*	Function:	Used to communicate with the hypervisor		*
	*	Methods:	backup										*
	*				get_radio_states							*
	*				machine_state								*
	*				reimage										*
	*				boot										*
	*				power_down									*
	*															*
	************************************************************/
	class hypervisor_com
	{
		function __construct()
		{
			include_once "/usr/share/pear/wseclab.php";
			$conf = new config();

			$this->hostname = $conf->HYPERVISOR_HOSTNAME;
			$this->boot = $conf->BOOT_PATH;
			$this->getdevicecount = $conf->DEVICECOUNT_PATH;
			$this->createclass = $conf->CREATE_PATH;
			$this->destroyclass = $conf->DESTROY_PATH;
			$this->poweroff = $conf->POWEROFF_PATH;
			$this->userspoweredon = $conf->USERSON_PATH;
			$this->clone = $conf->CLONE_PATH;
			$this->enable_logging = $conf->ENABLE_LOGGING;
			$this->log_file = "/var/log/wseclab.d/hypervisor_com.log";

		}
		/********************************************************
		*														*
		********************************************************/
		public function get_radio_states()
		{
			$response = $this->exec_script($this->hostname, $this->getdevicecount);
			return trim($response, "\n\r");
		}
		
		/********************************************************
		*														*
		********************************************************/
		//TODO Switch to single hypervisor code
		public function users_powered_on($type)
		{
			if(strcmp($type, "attack") == 0)
			{
				$optype = "attack";
			}
			else if(strcmp($type, "client") == 0)
			{
				$optype = "client";
			}
			else
				return "ERROR: Invalid machine type";
			
			$str = $this->exec_script($this->hostname, $this->userspoweredon);
			$ulist = explode(" ", $str);
			$retval = array();
			foreach($ulist as $value)
			{
				$uset = explode("_", $value);
				if(count($uset) == 2)
				{
					$uset[0] = ereg_replace("[^A-Za-z0-9]", "", $uset[0]);
					$uset[1] = ereg_replace("[^A-Za-z0-9]", "", $uset[1]);
					if(strcmp($uset[1], $optype) == 0)
						array_push($retval, $uset[0]);
				}
			}
			return $retval;
		}


		/********************************************************
		*														*
		********************************************************/
		public function machine_state($user, $type)
		{

			if(strcmp($type, "attack") == 0)
			{
				$optype = "_attack";
			}
			else if(strcmp($type, "client") == 0)
			{
				$optype = "_client";
			}

			$response = $this->exec_script($this->hostname, $this->userspoweredon);
			$users = explode(" ", $response);
			
			foreach($users as $value)
			{
				$value = trim($value, "\n\r");
				if(strcmp($value, $user.$optype) == 0)
					return true;
			}
			return false;
		}

		/********************************************************
		*														*
		********************************************************/
		//TODO Switch to single hypervisor code
		public function clone_img($user, $type, $action)
		{
			if(strcmp($type, "attack") == 0)
			{
				$optype = "-ta ";
			}
			else if(strcmp($type, "client") == 0)
			{
				$optype = "-tc ";
			}
			else
			{	
				$error = "Failed to clone machine for user '";
				$error = $error.$user."' Invalid machine type '";
				$error = $error.$type."'";
				$this->write_log($error);
				return 1;
			}
			
			if(strcmp($action, "backup") == 0)
				$response = $this->exec_script($this->hostname, $this->clone." -b -u".$optype.$user);
			else if(strcmp($action, "reimage") == 0)
				$response = $this->exec_script($this->hostname, $this->clone." -i -u".$optype.$user);
			else if(strcmp($action, "restore") == 0)
				$response = $this->exec_script($this->hostname, $this->clone." -r -u".$optype.$user);

			else
			{	
				$error = "Failed to clone machine for user '";
				$error = $error.$user."' Invalid action '";
				$error = $error.$action."'";
				$this->write_log($error);
				return 1;
			}

			if(strstr($response, "Action completed normally."))
				return 0;

			$error = "Failed to clone machine for user '";
			$error = $error.$user;
			$this->write_log($error);
			return 1;
		}

		
		/********************************************************
		*														*
		********************************************************/
		//TODO Switch to single hypervisor code
		public function boot($user, $type, $radios)
		{
			if(strcmp($type, "attack") == 0)
			{
				$optype = "-ta";
			}
			else if(strcmp($type, "client") == 0)
			{
				$optype = "-tc";
			}
			else
			{	
				$error = "Failed to boot machine for user '";
				$error = $error.$user."' Invalid machine type '";
				$error = $error.$type."'";
				$this->write_log($error);
				return 1;
			}

			$radiostr ="";

			foreach($radios as $value)
			{
				if(strcmp($value, "wifi") == 0)
					$radiostr = $radiostr." -rw";
				if(strcmp($value, "bluetooth") == 0)
					$radiostr = $radiostr." -rb";
				if(strcmp($value, "usrp") == 0)
					$radiostr = $radiostr." -ru";
				if(strcmp($value, "rfid") == 0)
					$radiostr = $radiostr." -rr";
			}

			$response = $this->exec_script($this->hostname, $this->boot." -u".$user." ".$radiostr." ".$optype);
			
			if(strstr($response, "Action completed normally"))
				return 0;

			$error = "Failed to boot $type machine for user '";
			$error = $error.$user;
			$this->write_log($error);
			return 1;
		}

		/********************************************************
		*														*
		********************************************************/
		public function power_down($user, $type)
		{
			if(strcmp($type, "attack") == 0)
			{
				$optype = "-ta";
			}
			else if(strcmp($type, "client") == 0)
			{	
				$optype = "-tc";
			}
			else
			{	
				$error = "Failed to power down $type machine for user '";
				$error = $error.$user."' Invalid machine type '";
				$error = $error.$type."'";
				$this->write_log($error);
				return 1;
			}

			$response = $this->exec_script($this->hostname, $this->poweroff." -u".$user." ".$optype);
			
			if(strstr($response, "Action completed normally"))
				return 0;
			
			$error = "Failed to power down machine for user: '";
			$error = $error.$user;
			$this->write_log($error);
			return 1;
		}

		public function create_class($users, $datastore)
		{
			$str = "";
			foreach($users as $user)
			{
				$str = $user." ".$str;
			}

			$response = $this->exec_script($this->hostname, $this->createclass." ".$datastore." ".$str);
			if(strstr($response, "Creating virtual machine"))
				return 0;
			return 1;
		}
		
		//TODO
		public function destroy_class($users)
		{
			$str = "";
			foreach($users as $user)
			{
				$response = $this->exec_script($this->hostname, $this->destroyclass." -u".$user);
			}
			return 0;
		}

		public function get_datastores()
		{
			$response = $this->exec_script($this->hostname, "ls /vmfs/volumes/ | grep datastore");
			$response = trim($response, " ");
			$retval = explode("\n", $response, -1);
			return $retval;
		}

		private function exec_script($hn, $cmd)
		{
			$this->write_log("Running command '".str_replace("\n", " ", $cmd));
			$response = shell_exec("ssh ".$hn." ".$cmd);
			$this->write_log("\tResponse: ".str_replace("\n", " ", $response));
			return $response;
		}

		private function write_log($str)
		{
			if(!$this->enable_logging)
				return;
			
			$fd = fopen($this->log_file, "a");
			$format = date("j-m-y h:i:s")."\t".$str."\n";
			fwrite($fd, $format);
			fclose($fd);
		}
	}
?>

