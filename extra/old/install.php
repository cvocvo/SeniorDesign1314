#!/usr/bin/php
<?php	

	include_once "php/wseclab.php";

	$conf = new config();
	
	$commands = array();	
	$commands[] = "echo '*/5 * * * * ".$conf->PHP_PATH."idle_user.php' | crontab -u ".$conf->WEB_USER." -"; 
	$commands[] = "cp php/* ".$conf->PHP_PATH;
	$commands[] = "cp -r www/* ".$conf->WEB_ROOT;
	$commands[] = "chown ".$conf->WEB_USER." ".$conf->PHP_PATH."idle_user.php";
	$commands[] = "chown ".$conf->WEB_USER." ".$conf->PHP_PATH."configure_machines.php";
	$commands[] = "chown ".$conf->WEB_USER." ".$conf->PHP_PATH."WebPortal.php";
	$commands[] = "chown ".$conf->WEB_USER." ".$conf->PHP_PATH."WebView.php";
	$commands[] = "chown ".$conf->WEB_USER." ".$conf->PHP_PATH."db_backend.php";
	$commands[] = "chown ".$conf->WEB_USER." ".$conf->PHP_PATH."hypervisor_com.php";
	$commands[] = "chmod 744 ".$conf->WEB_ROOT."index.php";
	$commands[] = "chmod 744 ".$conf->WEB_ROOT."renewsession.php";
	$commands[] = "chmod 744 ".$conf->WEB_ROOT."images/*";
	$commands[] = "chmod 755 ".$conf->WEB_ROOT."images";
	$commands[] = "chmod 744 ".$conf->WEB_ROOT."timer.js";
	$commands[] = "chmod 744 ".$conf->WEB_ROOT."style.css";
	$commands[] = "chmod 744 ".$conf->WEB_ROOT."userstats.php";
	$commands[] = "chmod 744 ".$conf->WEB_ROOT."log.php";
	$commands[] = "chmod 744 ".$conf->PHP_PATH."db_backend.php";
	$commands[] = "chmod 744 ".$conf->PHP_PATH."hypervisor_com.php";
	$commands[] = "chmod 744 ".$conf->PHP_PATH."idle_user.php";
	$commands[] = "chmod 744 ".$conf->PHP_PATH."configure_machines.php";
	$commands[] = "chmod 744 ".$conf->PHP_PATH."WebPortal.php";
	$commands[] = "chmod 744 ".$conf->PHP_PATH."WebView.php";
	$commands[] = "chmod 744 ".$conf->PHP_PATH."wseclab.php";
	
	if(is_dir("/var/log/wseclab.d"))
		$commands[] = "rm -r /var/log/wseclab.d";
	else if(is_file("/var/log/wseclab.d"))
		$commands[] = "rm /var/log/wseclab.d";
	$commands[] = "mkdir /var/log/wseclab.d";
	$commands[] = "touch /var/log/wseclab.d/hypervisor_com.log";
	$commands[] = "chown ".$conf->WEB_USER." /var/log/wseclab.d/hypervisor_com.log";
	$commands[] = "touch /var/log/wseclab.d/db_backend.log";
	$commands[] = "chown ".$conf->WEB_USER." /var/log/wseclab.d/db_backend.log";
	$commands[] = "touch /var/log/wseclab.d/cron_script.log";
	$commands[] = "chown ".$conf->WEB_USER." /var/log/wseclab.d/cron_script.log";
	$commands[] = "touch /var/log/wseclab.d/webportal.log";
	$commands[] = "chown ".$conf->WEB_USER." /var/log/wseclab.d/webportal.log";
	$commands[] = "touch /var/log/wseclab.d/create_machines.log";
	$commands[] = "chown ".$conf->WEB_USER." /var/log/wseclab.d/create_machines.log";
	
	foreach($commands as $cmd)
		shell_exec($cmd);
	
?>
