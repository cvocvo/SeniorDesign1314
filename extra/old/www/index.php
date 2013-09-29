<?php

	//TODO Switch over to javascript 

	session_start();

	include_once "/usr/share/pear/wseclab.php";
	$conf = new config();

	include ($conf->PHP_PATH."WebPortal.php");
	include ($conf->PHP_PATH."WebView.php");

	
	$webportal = new WebPortal();
	$page = null;
	
	if(isset($_POST['login']))
	{
		$error = $webportal->login($_POST['uname'], $_POST['upass']);
		if($error)
			echo "<script>alert('Login Failed')</script>";
	}
	else if(isset($_GET['q']))
	{
		if(strcmp($_GET['q'], "logout") == 0)
		{
			$webportal->logout();
			session_start();
		}
		else
			$page = $_GET['q'];
	}
	else if(isset($_POST['adduser']))
	{
		$error = $webportal->add_user($_POST['uname'], $_POST['upass'], $_POST['datastore']);
		if($error)
			echo "<script>alert('An error has occured. Please see logs...')</script>";
		else
			echo "<script>alert('User Successfully Added')</script>";
	}
	else if(isset($_POST['removeuser']))
	{
		$checkboxes = $_POST['user'];
		
		if(in_array("all_users", $checkboxes))
		{
			$error = $webportal->remove_class();
			if($error)
				echo "<script>alert('An error has occured. Please see logs...')</script>";
			else
				echo "<script>alert('All Users Successfully Removed')</script>";
		}
		else
		{
			foreach($checkboxes as $value)
			{
				$error = $webportal->remove_user($value);
				if($error)
					echo "<script>alert('An error has occured. Please see logs...')</script>";	
				else
					echo "<script>alert('$value Successfully Removed')</script>";

			}
		}
	}
	
	else if(isset($_POST['changepass']))
	{
		$error = $webportal->change_password($_POST['new'], $_POST['confirm']);
		if($error)
			echo "<script>alert('$error')</script>";
		else
			echo "<script>alert('Password successfully changed')</script>";
	}
	else if(isset($_POST['boot']))
	{
		$client_radios = array();
		$attack_radios = array();

		if(isset($_POST['client']))
		{
			$checkboxes = $_POST['client'];
			foreach($checkboxes as $value)
			{
				array_push($client_radios, $value);
			}
		}

		if(isset($_POST['attack']))
		{
			$checkboxes = $_POST['attack'];
			foreach($checkboxes as $value)
			{
				array_push($attack_radios, $value);
			}
		}

		$error = $webportal->boot_env($client_radios, $attack_radios);
		if($error)
			echo "<script>alert('An error has occured. Please see logs...')</script>";
	}
	else if(isset($_POST['powerdown']))
	{
		$error = $webportal->stop_env();
		if($error)
			echo "<script>alert('An error has occured. Please see logs...')</script>";
	}
	else if(isset($_POST['adminpowerdown']))
	{
		$checkboxes = $_POST['user'];

		foreach($checkboxes as $value)
		{
			$user_type = explode(":", $value);
			
			if(count($user_type) != 2)
				echo "<script>alert('Failed to power down $value')</script>";
			else
			{
				$error = $webportal->admin_powerdown($user_type[0], $user_type[1]);
				if($error)
					echo "<script>alert('An error has occured. Please see logs...')</script>";
			}
		}
	}
	else if(isset($_POST['backup']))
	{
		$error = $webportal->clone_img($_POST['type'], "backup");
		if($error)
			echo "<script>alert('An error has occured. Please see logs...')</script>";
	}
	else if(isset($_POST['restore']))
	{
		$error = $webportal->clone_img($_POST['type'], "restore");
		if($error)
			echo "<script>alert('An error has occured. Please see logs...')</script>";
	}
	else if(isset($_POST['reimage']))
	{
		$error = $webportal->clone_img($_POST['type'], "reimage");
		if($error)
			echo "<script>alert('An error has occured. Please see logs...')</script>";
	}
	else if(isset($_POST['deteleclass']))
	{
		echo "<script>alert('deleting class')</script>";		
	}
	else if(isset($_POST['createclass']))
	{
		if ($_FILES["file"]["type"] == "text/plain")
  		{
  			if ($_FILES["file"]["error"] > 0)
    		{
    			$page = "manageclass";
				echo '<script>alert("Failed to create class...")</alert>';
    		}
  			else
   			{
    			//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    			//echo "Type: " . $_FILES["file"]["type"] . "<br />";
    			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    			//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
      			$error = $webportal->create_class($_FILES["file"]["tmp_name"], $_POST['datastore']);

				if($error)
					echo "<script>alert('An error has occured. Please see logs...')</script>";
    		}
  		}
		else
		{
			echo '<script>alert("File must be plain text")</alert>';
  			$page = "manageclass";
  		}
	}
	
	$webview = new WebView();
	if(strcmp("admin", $webportal->session_type()) == 0)
	{
		if(strcmp($page, "adduser") == 0)
		{
			$webview->print_header();
			$webview->print_logout($webportal->get_username());
			$webview->print_admin_adduser($webportal->get_datastores());
			$webview->print_footer();
		}
		else if(strcmp($page, "removeuser") == 0)
		{
			$webview->print_header();
			$webview->print_logout($webportal->get_username());
			$webview->print_admin_removeuser($webportal->get_userlist());
			$webview->print_footer();
		}
		else if(strcmp($page, "changepass") == 0)
		{
			$webview->print_header();
			$webview->print_logout($webportal->get_username());
			$webview->print_changepass();
			$webview->print_footer();
		}
		else if(strcmp($page, "manageclass") == 0)
		{
			$webview->print_header();
			$webview->print_logout($webportal->get_username());
			$webview->print_admin_class_manager($webportal->get_datastores());
			$webview->print_footer();
		}
		else
		{
			$webview->print_header();
			$webview->print_logout($webportal->get_username());
			$attack = $webportal->get_states("attack");
			$client = $webportal->get_states("client");
			$webview->print_admin_home($attack, $client);
			$webview->print_footer();
		}
	}
	else if(strcmp("user", $webportal->session_type()) == 0)
	{	
		if(strcmp($page, "changepass") == 0)
		{
			$webview->print_header();
			$webview->print_logout($webportal->get_username());
			$webview->print_changepass();
			$webview->print_footer();
		}
		else
		{
			$webview->print_header();
			$webview->print_logout($webportal->get_username());
			$machines = $webportal->check_equipment();
			$radios = $webportal->check_radios();
			$webview->print_user_home($machines, $radios);
			$webview->print_footer();
		}
	}	
	else if(strcmp("firstlogin", $webportal->session_type()) == 0)
	{
		$webview->print_header();
		$webview->print_logout($webportal->get_username());
		$webview->print_changepass();
		$webview->print_footer();
	}
	else
	{
		$webview->print_header();
		$webview->print_login();
		$webview->print_loginpage();
		$webview->print_footer();
	}
?>
