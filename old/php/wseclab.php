<?php
	
	# This is the main configuration file for the webportal
	# These values are read once when the configure.php script is run
	
	# Number of seconds the user's session lasts before the user must refresh 
	# or power down his or her machines.
	class Config
	{
		function __construct()
		{
			$this->MAX_SESSION_LENGTH = 7200;
			$this->SESSION_REFRESH_LENGTH = 7200;
			$this->HYPERVISOR_HOSTNAME = "root@129.186.215.200";
					#"root@wseclab-admin.student.iastate.edu";
			
			$this->BOOT_PATH = "/vmfs/volumes/datastore1/wnsl-tools/provisionandboot.sh";
			$this->DEVICECOUNT_PATH = "/vmfs/volumes/datastore1/wnsl-tools/getdevicecount.sh";
			$this->POWEROFF_PATH = "/vmfs/volumes/datastore1/wnsl-tools/poweroffvm.sh";
			$this->USERSON_PATH = "/vmfs/volumes/datastore1/wnsl-tools/userspoweredon.sh";
			$this->CLONE_PATH = "/vmfs/volumes/datastore1/wnsl-tools/clonevm.sh";
			$this->CREATE_PATH = "/vmfs/volumes/datastore1/wnsl-tools/verifyinit.sh";
			$this->DESTROY_PATH = "/vmfs/volumes/datastore1/wnsl-tools/destroyuservms.sh";
			$this->DONE_PATH = "/vmfs/volumes/datastore1/wnsl-tools/INIT_DONE";

			$this->ENABLE_LOGGING = 1;

			$this->MYSQL_HOSTNAME = "localhost";
			$this->MYSQL_USERNAME = "root";
			$this->MYSQL_DATABASE = "wseclab";
			$this->MYSQL_PASSWORD = "password"; #lulz
			$this->MYSQL_TABLE 	= "user";
			$this->MYSQL_PORT_TABLE ="portdef";
			
			$this->ADMIN_EMAIL = "ajlobono@gmail.com";

			$this->ATTACK_SUFFIX = "_attack";
			$this->CLIENT_SUFFIX = "_client";

			$this->WEB_USER = "user1";
			$this->WEB_ROOT = "/var/www/";
			$this->WEB_HOME = "/home/user1/";

			$this->PHP_PATH = "/usr/share/pear/";
			
		}
	}
	
?>
