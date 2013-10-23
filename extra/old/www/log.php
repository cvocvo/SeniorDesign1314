<?php

	session_start();

	include_once "/usr/share/pear/wseclab.php";
	$conf = new config();
	include_once ($conf->PHP_PATH."WebPortal.php");
	
	$webportal = new WebPortal();

	if($webportal->is_admin())
	{
		echo "Please login as an administrator to view the logs.";
		exit();
	}
	
	echo '<a href="index.php">Home</a>&nbsp;|&nbsp;';
	echo '<a href="log.php?log=hypervisor">Hypervisor Log</a>&nbsp;|&nbsp;';
	echo '<a href="log.php?log=webportal">WebPortal Log</a>&nbsp;|&nbsp;';
	echo '<a href="log.php?log=vm-creation">Vitual Machine Creation Log</a><br/><br/>';

	if(isset($_GET['log']))
	{
		$log = $_GET['log'];
		$loglines = array();
		if($log == "hypervisor")
		{
			$loglines = file("/var/log/wseclab.d/hypervisor_com.log");
		}
		else if($log == "webportal")
		{
			$loglines = file("/var/log/wseclab.d/webportal.log");
		}
		else if($log == "vm-creation")
		{
			$loglines = file("/var/log/wseclab.d/create_machines.log");
		}

		foreach($loglines as $line)
		{
			echo $line;
		}
	}
?>



