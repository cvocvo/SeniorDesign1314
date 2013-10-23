#!/usr/bin/php

<?php
	
	function ready_to_configure($conf)
	{
		$output = array();
		exec("ssh ".$conf->HYPERVISOR_HOSTNAME." ls ".$conf->DONE_PATH, $output, $status);

		if($status != 0)
			return false;
		
		shell_exec("ssh $conf->HYPERVISOR_HOSTNAME rm $conf->DONE_PATH");
		return true;
	}

	function write_log($str)
	{
		$fd = fopen("/var/log/wseclab.d/create_machines.log", "a");
		$format = "[".date("j-m-y h:i:s")."] ".$str."\n";
		fwrite($fd, $format);
		fclose($fd);
	}


	include_once "/usr/share/pear/wseclab.php";

	$conf = new config();

	include_once($conf->PHP_PATH."db_backend.php");
	include_once($conf->PHP_PATH."hypervisor_com.php");
	
	$EMAIL = $conf->ADMIN_EMAIL;

	
	if(!ready_to_configure($conf))
	{
		write_log("Not ready to configure");
		exit();
	}
	

	write_log("Starting configuration");

	$db = new db_backend();
	$hypev = new hypervisor_com();
	$conf = new config();

	$lines = file($conf->WEB_HOME."userlist.txt");

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
				write_log("ERROR: INvalid user: ".$user[0]." at line: $key (non alpha-numeric character)");
			if(ereg("[^A-Za-z0-9]", $user[1], $empty))
				write_log("ERROR: Invalid user id: ".$user[1]." at line: $key (non alpha-numeric character)");
			if(strlen($user[0]) < 1)
				write_log("ERROR: Username blank at line: $line");
			if(strlen($user[0]) > 16)
				write_log("ERROR: Username longer than 16 characters at line: $line");
			if(strlen($user[1]) < 1)
				write_log("ERROR: password blank at line: $line");
			
			if(in_array($user[0], $userlist['user']))
				write_log("ERROR: Duplicate user: ".$user[0]." at line: $key");
			if(in_array($user[1], $userlist['pass']))
				write_log("ERROR: Duplicate user id: ".$user[1]." at line $key");

			$userlist['user'][] = $user[0];
			$userlist['pass'][] = $user[1];
		}
	}
	
	$radios = array();

	if(count($userlist['user']) > 100)
	{
		write_log("System does not currently support more than 100 users...");
		$to = $conf->ADMIN_EMAIL;
 		$subject = "WSECLAB";
 		$body = "Failed to configure users machines. System currently only supports 100 users.";
 		mail($to, $subject, $body);
  	 	exit();
	}

		
	$cur_def = $db->get_last_portdef();
	$abase = 1024;
	$cbase = 1124;
	
	if(count($cur_def) > 0)
	{
		$abase = $cur_def['aport'] + 1;
		$cbase = $cur_def['cport'] + 1;
		write_log("CURDEF: abase ".$cur_def['aport']." cbase ".$cur_def['cport']);
	}



	for($index = 0; $index < count($userlist['user']); $index++)
	{

		$user = $userlist['user'][$index];
		$pass = $userlist['pass'][$index];		

		$aport = $index + $abase;
		$cport = $index + $cbase;
		$num = $index + 51 + ($abase - 1024);
		$aip = "192.168.1.".$num;
		$num = $index + 151 + ($cbase - 1124);
		$cip = "192.168.1.".$num;
		
		if($db->user_exists($user))
		{
			$tempdef = $db->get_portdef($user);
			$cport = $tempdef['cport'];
			$aport = $tempdef['aport'];
			$num = 51 + ($aport - 1204);
			$aip = "192.168.1.".$num;
			$num = 151 + ($cport - 1124);
			$cip = "192.168.1.".$num;
		}	
		
		if($num > 251)
		{
			write_log("System does not currently support more than 100 users...");
			$to = $EMAIL;
 			$subject = "WSECLAB";
 			$body = "Failed to configure users machines. System currently only supports 100 users.";
 			mail($to, $subject, $body);
			exit();
		}
		
		write_log("Booting ".$user."_attack");
		$error = $hypev->boot($user, "attack", $radios);
		if($error)
		{
			write_log("Failed to boot attack machine. See hypervisor_com.log");
			shell_exec("echo '*/5 * * * * /usr/share/pear/idle_user.php' | crontab -u www-data -");
			exit();
		}
		write_log("Booting ".$user."_client");
		$error = $hypev->boot($user, "client", $radios);
		if($error)
		{
			write_log("Failed to boot client machine.  See hypervisor_com.log");
			shell_exec("echo '*/5 * * * * /usr/share/pear/idle_user.php' | crontab -");
			exit();
		}
		sleep(180);

		$str = "auto lo\niface lo inet loopback\nauto eth0\niface eth0 inet static\naddress $cip\n";
		$str = $str."netmask 255.255.255.0\ngateway 192.168.1.254\n";
		
		$str2 = "#!/bin/sh -e\n#rc.local\n\nip route add default via 192.168.1.254 table rt_eth\nip rule add from ".$cip. " table rt_eth\n\n";
		$str2 = $str2."rmmod rtl8187\nrfkill block all\nrfkill unblock all\nmodprobe rtl8187\nrfkill unblock all\nifconfig wlan0 up\n";
		$str2 = $str2."ifconfig wlan1 up\nifconfig wlan2 up\nifconfig wlan3 up\nifconfig wlan4 up\nexit 0";
		
		write_log("Configureing user: $user\taport: $aport\tcport: $cport\taip: $aip\tcip: $cip");

		$commands = array();
		$commands[] = "echo 'yes' | ssh root@192.168.1.2 usermod -l $user user1";
		$commands[] = "ssh root@192.168.1.2 "."'".'echo -e "'.$pass."\n".$pass.'"'.' | passwd '.$user."'";
		$commands[] = 'ssh root@192.168.1.2 '."'"."echo -e ".'"'.$str.'"'." > /etc/network/interfaces"."'";
		$commands[] = 'ssh root@192.168.1.2 '."'"."echo -e ".'"'.$str2.'"'." > /etc/rc.local"."'";
		$commands[] = 'ssh root@192.168.1.2 '."'"."echo ".'"PermitRootLogin no" >> /etc/ssh/sshd_config'."'";
		$commands[] = 'ssh root@192.168.1.2 rm /root/.ssh/authorized_keys';

		$str = "auto lo\niface lo inet loopback\nauto eth0\niface eth0 inet static\naddress $aip\n";
		$str = $str."netmask 255.255.255.0\ngateway 192.168.1.254\n";
		
		$str2 = "#!/bin/sh -e\n#rc.local\n\nip route add default via 192.168.1.254 table rt_eth\nip rule add from ".$aip. " table rt_eth\n\n";
		$str2 = $str2."rmmod rtl8187\nrfkill block all\nrfkill unblock all\nmodprobe rtl8187\nrfkill unblock all\nifconfig wlan0 up\n";
		$str2 = $str2."ifconfig wlan1 up\nifconfig wlan2 up\nifconfig wlan3 up\nifconfig wlan4 up\nexit 0";

		$commands[] = "echo 'yes' | ssh root@192.168.1.3 usermod -l $user user1";
		$commands[] = "ssh root@192.168.1.3 "."'".'echo -e "'.$pass."\n".$pass.'"'.' | passwd '.$user."'";
		$commands[] = 'ssh root@192.168.1.3 '."'"."echo -e ".'"'.$str.'"'." > /etc/network/interfaces"."'";
		$commands[] = 'ssh root@192.168.1.3 '."'"."echo -e ".'"'.$str2.'"'." > /etc/rc.local"."'";
		$commands[] = 'ssh root@192.168.1.3 '."'"."echo ".'"PermitRootLogin no" >> /etc/ssh/sshd_config'."'";
		$commands[] = 'ssh root@192.168.1.3 rm /root/.ssh/authorized_keys';
		

		$db->add_portdef($user, $aport, $cport);
		$db->add_user($user, $pass);
		
		foreach($commands as $cmd)
		{
			$response = array();
			write_log("executing ".str_replace("\n", " ", $cmd));
			exec($cmd, $response, $status);
			foreach($response as $value)
				write_log("\t".$resonse);
			write_log("\tstatus:".$status);
		}
		sleep(10);	
		$hypev->power_down($user, "attack");
		$hypev->power_down($user, "client");

		shell_exec("echo '*/5 * * * * ".$conf->PHP_PATH."idle_user.php' | crontab -");

		$to = $conf->ADMIN_EMAIL;
 		$subject = "WSECLAB";
 		$body = "Creating and configureing machines has completed.";
 		mail($to, $subject, $body);

	}

	

?>
