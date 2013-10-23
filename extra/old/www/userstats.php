<?php
	
	session_start();
	include_once "/usr/share/pear/wseclab.php";
	$conf = new config();
	include_once($conf->PHP_PATH."WebPortal.php");

	$wp = new WebPortal();

	if(!$wp->is_valid_session() || !$wp->is_user())
		die();
	
	$machines = $wp->check_equipment();
	$radios = $wp->check_radios();
	$lastsession = $wp->get_lastsession();
	$ports = $wp->get_portdef();
	

	$retval = array();
	$retval['machines'] = $machines;
	$retval['radios'] = $radios;
	$retval['lastsession'] = $lastsession;
	$retval['ports'] = $ports;

	echo JSON_encode($retval);

?>
