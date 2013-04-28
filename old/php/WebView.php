<?php


	class WebView
	{	
		public function print_header()
		{
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			echo '<html xmlns="http://www.w3.org/1999/xhtml">';
			echo '<head>';
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			echo '<title>Home | sdDec1110: Wireless Network Security Laboratory</title>';
			echo '<link href="style.css" rel="stylesheet" type="text/css" />';
			echo '<script type="text/javascript" language="javascript" src="timer.js"></script>';
			echo '</head>';
			echo '<body onload="initTimer()">';

			
		}

		public function print_login()
		{
			echo '<div id="loginwrap">';
			echo '<div id="login">';
			echo '<form action="index.php" name="login" method="post">';
			echo '<input type="text" name="uname" class="rounded" placeholder="Username" />&nbsp;';
			echo '<input type="password" name="upass" class="rounded" placeholder="Password"/>';
			echo '<input type="submit" value="login" name="login" /></form>';
			echo '</div>';
		}

		public function print_loginpage()
		{
			echo '<div id="loginheader">';
			echo '</div>';

			echo '<div id="loginradios">';
			echo '</div>';

		}
		public function print_footer()
		{

			echo '<div id="footer">';
			echo 'All content herein shall be considered the intellectual property of Iowa State University and/or sdDec11-10.  Copyright 2011.';
			echo '</div>';
			echo '</div>';
			echo '</body>';
			echo '</html>';

		}
		public function print_logout($username)
		{
			echo '<div id="overall">';
			echo '<div id="logout"><span id="clock"></span>';
			echo '<span style="float:right;">';
			echo $username;
			echo '&nbsp|&nbsp<a href="index.php?q=logout">logout</a></span>';
			echo '</div>';
		}
		public function print_changepass()
		{
			echo '<div id="header"></div>';

			echo '<div id="linkbar">';
			
			echo '</div>';
			
			echo '<div id="content">';

			echo '<form action="index.php" name="changepass" method="post">';
			echo '<table><td>New Password: </td><td><input type="password" name="new" /></td></tr>';
			echo '<td>Confirm:	</td><td><input type="password" name="confirm" /></td></tr>';
			echo '<tr><td><input type="submit" value="Change Password" name="changepass" /></td></tr></table>';

			echo '</div>';
		}
		
		
		private function print_admin_linkbar()
		{
			echo '<div id="header"></div>';
			
			echo '<div id="linkbar">';
			echo '<a href="index.php">Home</a>';
			echo '&nbsp;&nbsp;&nbsp;&#8226;&nbsp;&nbsp;&nbsp;';
			echo '<a href="index.php?q=adduser">Add User</a>';
			echo '&nbsp;&nbsp;&nbsp;&#8226;&nbsp;&nbsp;&nbsp;';
			echo '<a href="index.php?q=removeuser">Remove User</a>';
			echo '&nbsp;&nbsp;&nbsp;&#8226;&nbsp;&nbsp;&nbsp;';
			echo '<a href="index.php?q=changepass">Change Password</a>';
			echo '&nbsp;&nbsp;&nbsp;&#8226;&nbsp;&nbsp;&nbsp;';
			echo '<a href="index.php?q=manageclass">Manage Class</a>';

			echo '</div>';
		}


		public function print_admin_home($attack, $client)
		{

			$this->print_admin_linkbar();
			
			echo '<div id="content">';
			

			echo '<form action="index.php" name="adminpowerdown" method="post">';
			echo '<div id="leftcol">';
			echo '<h1>Attack Machines</h1><br/>';
			foreach($attack as $key => $value)
			{
				$checkval = $key.":attack";
				
				if($value)
				{
					echo $key."&nbsp;&nbsp;<input type='checkbox' name='user[]' value='$checkval' /> <br/>";
					echo '<img src="images/machine_on.png"><br/>';
				}
				else
				{
					echo $key."&nbsp;&nbsp;<input type='checkbox' name='user[]' value='$checkval' DISABLED /> <br/>";
					echo '<img src="images/machine_off.png"><br/>';
				}
			}
			echo '</div>';
			
			echo '<div id="rightcol">';
			echo '<h1>Client Machines</h1><br/>';
			foreach($client as $key => $value)
			{
				$checkval = $key.":client";
				if($value)
				{
					echo $key."&nbsp;&nbsp;<input type='checkbox' name='user[]' value='$checkval' /> <br/>";
					echo '<img src="images/machine_on.png"><br/>';
				}
				else
				{
					echo $key."&nbsp;&nbsp;<input type='checkbox' name='user[]' value='$checkval' DISABLED /> <br/>";
					echo '<img src="images/machine_off.png"><br/>';
				}
			}
	
			echo '</div>';
			echo '<input type="submit" name="adminpowerdown" value="Power Down Machines" />';
			echo '</form>';
			echo '</div>';
		}
		
		public function print_admin_adduser($datastores)
		{
			$this->print_admin_linkbar();
			echo '<div id="content">';

			echo '<form action="index.php" name="adduser" method="post">';
			echo '<table><tr><td>User Name: </td><td><input type="text" name="uname" /></td></tr>';
			echo '<tr><td>Student ID:	</td><td><input type="text" name="upass" /></td></tr>';
			echo '<tr><td>Data Store:</td><td><select name="datastore">';
			foreach($datastores as $value)
				echo "<option value='$value'>$value</option>";
			echo '</select></td></tr>';
			echo '<tr><td><input type="submit" value="Add User" name="adduser" /></td></tr></table>';

			echo '</div>';
		}

		public function print_admin_removeuser($users)
		{
			$this->print_admin_linkbar();
			echo '<div id="content">';

			echo '<form action="index.php" name="removeuser" method="post">';
			echo '<table width="100%"><tr>';
			$count = 1;
			foreach($users as $key => $value)
			{
				if(strcmp($value, "admin") != 0)
				{
					echo "<td align='right'><input type='checkbox' value='$key' name='user[]' /></td>";
					echo "<td align='left'>$key</td>";
					if($count % 3 == 0)
						echo "</tr><tr>";
					$count++;
				}
			}
			echo "</tr>";
			echo "<tr><td colspan='3' align='right'><input type='checkbox' value='all_users' name='user[]' /></td>";
			echo "<td colspan='3' align='left'>All Users</td></tr>";
			echo "<tr><td colspan='6'><input type='submit' name='removeuser' value='Remove Users' /></td></tr>";
			echo "</table>";
			echo "</form>";
			echo '</div>';

		}

		public function print_admin_class_manager($datastores)
		{
			$this->print_admin_linkbar();
			echo '<div id="content">';	
			echo '<h1>Creat New Class</h1>';	
			echo '<form action="index.php" method="post" enctype="multipart/form-data">';
			echo '<label for="file">Filename:</label>';
			echo '<input type="file" name="file" id="file" />';
			echo '<br />';
			echo 'Data Store:<select name="datastore">';
			foreach($datastores as $value)
				echo "<option value='$value'>$value</option>";
			echo '</select>';
			echo '<input type="submit" name="createclass" value="Submit" />';
			echo '</form>';

			echo '<br /><h1>Delete Class</h1>';
			echo '<form action="index.php" method="post">';
			echo '<input type="submit" name="deleteclass" value="Delete" />';
			echo '</form>';
			echo '</div>';
		}

		public function print_user_home($machines, $radios)
		{
			echo '<div id="header"></div>';
			
			echo '<div id="linkbar">';
			
			echo '<a href="index.php">Home</a>';
			echo '&nbsp;&nbsp;&nbsp;&#8226;&nbsp;&nbsp;&nbsp;';
			echo '<a href="index.php?q=changepass">Change Password</a>';
	
			echo '</div>';
			
			echo '<div id="content">';

			echo '<div id="leftcol">';
			echo 'Attack<br/>';
			if($machines['attack'])
				echo '<img id="attack_img" src="images/machine_on.png"><br/>';
			else
				echo '<img id="attack_img" src="images/machine_off.png"><br/>';
			$this->print_backup("attack");
			echo "<br/>";
			$this->print_reimage("attack");
			echo "<br/>";
			$this->print_restore("attack");
			echo '</div>';
	
			echo '<div id="centercol">';
				
			if(!$machines['attack'] && !$machines['client'])
			{
				echo '<br/>';
				echo '<form action="index.php" name="boot" method="post">';
				echo '<table width=100%>';
				echo '<tr><td align="left">';
				echo '<input type="checkbox" name="attack[]" value="wifi" ';
				if($radios['wifi'] < 1)
					echo "DISABLED";
				echo ' />';
				echo '</td><td>Wifi</td><td align="right">';
				echo '<input type="checkbox" name="client[]" value="wifi" ';
				if($radios['wifi'] < 2)
					echo "DISABLED";
				echo ' />';
				echo '</td></tr>';
				echo '<tr><td align="left">';
				echo '<input type="checkbox" name="attack[]" value="bluetooth" ';
				if($radios['bluetooth'] < 1)
					echo "DISABLED";
				echo '/>';
				echo '</td><td>Bluetooth</td><td align="right">';
				echo '<input type="checkbox" name="client[]" value="bluetooth" ';
				if($radios['bluetooth'] < 2)
					echo "DISABLED";
				echo '/>';
				echo '</td></tr>';
				echo '<tr><td align="left">';
				echo '<input type="checkbox" name="attack[]" value="rfid" ';
				if($radios['rfid'] < 1)
					echo "DISABLED";
				echo '/>';
				echo '</td><td>RFID</td><td align="right">';
				echo '<input type="checkbox" name="client[]" value="rfid" ';
				if($radios['rfid'] < 2)
					echo "DISABLED";
				echo '/><br/>';
				echo '</td></tr>';
				echo '<tr><td align="left">';
				echo '<input type="checkbox" name="attack[]" value="usrp" ';
				if($radios['usrp'] < 1)
					echo "DISABLED";
				echo '/>';
				echo '</td><td>USRP</td><td align="right">';
				echo '<input type="checkbox" name="client[]" value="usrp" ';
				if($radios['usrp'] < 2)
					echo "DISABLED";
				echo '/>';
				
				echo '</td></tr></table>';
				echo '<input class="submit" type="submit" value="Start Environment" name="boot" />';
				echo '</form>';
			}	
			else
			{
				echo '<form action="index.php" name="powerdown" method="post">';
				echo '<input type="submit" value="Stop Environment" name="powerdown" />';
				echo '</form>';
			}
			echo '</div>';

			echo '<div id="rightcol">';
			echo 'Client<br />';
			if($machines['client'])
				echo '<img id="client_img" src="images/machine_on.png"><br/>';
			else
				echo '<img id="client_img" src="images/machine_off.png"><br/>';
			$this->print_backup("attack");
			echo "<br/>";
			$this->print_reimage("attack");
			echo "<br/>";
			$this->print_restore("attack");
			echo '</div>';

			echo '</div>';

		}

		
		private function print_backup($machine)
		{
			echo '<form action="index.php" name="backup" method="post">';
			echo "<input type='hidden' name='type'  value='$machine' />";
			echo '<input class="submit" type="submit" value="Backup Image" name="backup" />';
			echo '</form>';
		}
		private function print_restore($machine)
		{
			echo '<form action="index.php" name="restore" method="post">';
			echo "<input type='hidden' name='type' value='$machine' />";
			echo '<input class="submit" type="submit" value="Restore Image" name="restore" />';
			echo '</form>';
		}
		private function print_reimage($machine)
		{
			echo '<form action="index.php" name="reimage" method="post">';
			echo "<input type='hidden' name='type' value='$machine' />";
			echo '<input class="submit" type="submit" value="Reimage Machine" name="reimage" />';
			echo '</form>';
		}
		private function print_radios()
		{
			if($this->radios == null)
			{
				echo "Failed to retrieve radios";
				return;
			}

			echo print_r(array_keys($this->radios));
		}
		
	}
?>
