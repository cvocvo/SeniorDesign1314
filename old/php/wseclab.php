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
			$this->HYPERVISOR_HOSTNAME = "root@wseclab-admin.student.iastate.edu";
			
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
			$this->MYSQL_USERNAME = "wseclab";
			$this->MYSQL_DATABASE = "wseclab";
			$this->MYSQL_PASSWORD = "I47F1uDtaJj4671";
			$this->MYSQL_TABLE 	= "user";
			$this->MYSQL_PORT_TABLE ="portdef";
			
			$this->ADMIN_EMAIL = "ajlobono@gmail.com";

			$this->ATTACK_SUFFIX = "_attack";
			$this->CLIENT_SUFFIX = "_client";

			$this->WEB_USER = "http";
			$this->WEB_ROOT = "/srv/http/";
			$this->WEB_HOME = "/home/http/";

			$this->PHP_PATH = "/usr/share/pear/";
			
		}
	}
	
?>
