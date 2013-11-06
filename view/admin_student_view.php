<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Wireless Security Lab - DEC13/14 SE 492</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<script src="js/jquery-2.0.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>
  </head>

  <body>

    <div class="container">
      <div class="header">
        <ul class="nav nav-pills pull-right">
			<li class="active"><a href="<?print(SITE_ROOT . '/index.php?admin_class_manager');?>">Class Manager</a></li>
			<li><a href="<?print(SITE_ROOT . '/index.php?admin_tools');?>">Admin Tools</a></li>
			<li><a href="<?print(SITE_ROOT . '/index.php?logout');?>">Log Out</a></li>
        </ul>
		<img src="img/logo.png" alt="logo"/>
        <h3 class="text-muted">Wireless Security Lab</h3>
      </div>
	  
	  <div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li><a href="#">Class Manager</a></li>
			  <li><a href="#"><?=$data['class'];?></a></li>
			  <li class="active">[<?=$data['user'];?>]</li>
			</ol>
			<h4>Currently Viewing Student: [<?=$data['user'];?>]</h4>
		</div>
	  </div>
	  
	  <div class="jumbotron row padT20">
		<div class="col-md-12">
			<h2>Edit Student Details</h2>
			<form action="index.php" method="post">
				<input type="hidden" name="page" value="admin_student_view"/>
				<input type="hidden" name="student" value="<?=$data['user'];?>"/>
				<p>*Only fields with information in them will be changed.</p>
				<div class="form-group">
					<p>Student Name:</p>
					<input type="text" class="form-control" name="studentname" placeholder="John Doe">
				</div>
				<div class="form-group">
					<p>Username (email address):</p>
					<input type="text" class="form-control" name="username" placeholder="somestudentemail@iastate.edu">
				</div>
				<p>Change Password:</p>
				<div class="form-group">
					<input type="password" class="form-control" name="newpassword" placeholder="*Enter NEW Password">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="newpassword2" placeholder="*Enter NEW Password Again">
				</div>
				<p>Change the class this student is in:</p>
				<div class="form-group">
					<select class="form-control">
					<?php
					foreach($data['classes'] as $class){
					  echo '<option>' . $class . '</option>';
					}
					?>
					</select>
				</div>
				<button type="submit" class="btn btn-success">Save Changes</button>
			</form>
		</div>
      </div>

      <?php
      foreach($data['machine_tables'] as $table){
      	echo $table;
      }
      ?>
	  
      <div class="footer">
        <p>&copy; 2013 Iowa State University and/or DEC13-14. All Rights Reserved.</p>
      </div>

    </div> <!-- /container -->
  </body>
</html>
