<?php

	session_start();
	include_once "/usr/share/pear/wseclab.php";
	$conf = new config();
	include_once ($conf->PHP_PATH."WebPortal.php");

	$wp = new WebPortal();

	if(!$wp->is_valid_session() || !$wp->is_user())
		die();
	
	echo $wp->renew_session();
?>
