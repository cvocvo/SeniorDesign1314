#!/usr/bin/php
<?php

	include_once "/usr/share/pear/wseclab.php";
	$conf = new config();
	include_once ($conf->PHP_PATH."db_backend.php");
	include_once ($conf->PHP_PATH."hypervisor_com.php");

	$hypev = new hypervisor_com();
	$db = new db_backend();
	
	$users_on = $hypev->users_powered_on("attack");
	
	foreach($users_on as $user)
	{
		$str_time = $db->lastsession($user);
		if($str_time != null)
		{
			$lastsession = strtotime($str_time);
			$str_time = date('Y-m-d H:i:s');
			$curtime = strtotime($str_time);
			$dif = $curtime - $lastsession;
			if($dif > $conf->MAX_SESSION_LENGTH)
			{
				$hypev->power_down($user, "attack");
				$hypev->power_down($user, "client");
			}
		}
	}

?>
