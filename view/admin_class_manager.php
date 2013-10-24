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
	  
	  <div class="jumbotron row">
		<div class="col-md-12">
			<h2>Class Manager</h2>
			<p>Current Classes:</p>
			<ul id="classManagerList" class="list-unstyled">
				<form action="index.php" method="post">
					<input type="hidden" name="page" value="admin_class_manager" />
					<input type="hidden" name="form_id" value="delete_class" />
					<?php
					foreach ($data['classes'] as $class){
						echo '<li><a href="'. SITE_ROOT . '/index.php?admin_class_view&class=' . $class . '">' . $class .
							'</a><button type="submit" value="' . $class . '" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-remove"></span> Remove Class</button</li>';
					}
					?>
				</form>
			</ul>
			<p><strong>Note:</strong> By clicking 'Remove Class' you will remove the class, all of the students user accounts, and all associated virtual machines.</p>
		</div>
      </div>
	  
	  <div class="jumbotron row padT20">
		<div class="col-md-12">
			<h2>Add Class</h2>
			<p>Import a comma-delimited class file. <strong>Note:</strong> This will automatically create users for this class.</p>
			<form role="form">
				<div class="form-group">
					<label for="classListFile">File input</label>
					<input type="file" id="classListFile">
					<p class="help-block">File format should be: lorem, ipsum, bacon</p>
				</div>
				<p>Select Virtual Machines to grant class access to:</p>
				<div class="checkbox">
				  <label>
					<input type="checkbox" value="">
					[Virtual Machine Name Goes Here]
				  </label>
				</div>
				<div class="checkbox">
				  <label>
					<input type="checkbox" value="">
					[Virtual Machine Name Goes Here]
				  </label>
				</div>
				<div class="checkbox">
				  <label>
					<input type="checkbox" value="">
					[Virtual Machine Name Goes Here]
				  </label>
				</div>
				<button type="submit" class="btn btn-success">Create Class</button>
			</form>
			
			<h4 class="padT20">OR, just add a class for now:</h4>
			<form role="form">
				<div class="form-group">
					<p>Class Name:</p>
					<input type="password" class="form-control" name="classname" placeholder="Enter your class name">
				</div>
				<p>Select Virtual Machines to grant class access to:</p>
				<div class="checkbox">
				  <label>
					<input type="checkbox" value="">
					[Virtual Machine Name Goes Here]
				  </label>
				</div>
				<div class="checkbox">
				  <label>
					<input type="checkbox" value="">
					[Virtual Machine Name Goes Here]
				  </label>
				</div>
				<div class="checkbox">
				  <label>
					<input type="checkbox" value="">
					[Virtual Machine Name Goes Here]
				  </label>
				</div>
				<button type="submit" class="btn btn-success">Create Class</button>
			</form>
		</div>
      </div>
	  
	  <div class="jumbotron row padT20">
		<div class="col-md-12">
			<h2>Add Students</h2>
			<h4>Add a new student to an existing class:</h4>
			<form role="form">
			    <div class="form-group">
				    <p>Student Name:</p>
					<input type="text" class="form-control" name="studentname" placeholder="John Doe">
				</div>
				<div class="form-group">
					<p>Username (email address):</p>
					<input type="text" class="form-control" name="username" placeholder="somestudentemail@iastate.edu">
				</div>
				<p>Password:</p>
				<div class="form-group">
					<input type="password" class="form-control" name="newpassword" placeholder="*Enter Password">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="newpassword2" placeholder="*Enter Password Again">
				</div>
				<p>Add to class:</p>
				<div class="form-group">
					<select class="form-control">
					  <option>CPRE 530 Section 1</option>
					  <option>CPRE 530 Section 2</option>
					</select>
				</div>
				<button type="submit" class="btn btn-success">Create New Student</button>
			</form>
		</div>
      </div>

      <div class="footer">
        <p>&copy; 2013 Iowa State University and/or DEC13-14. All Rights Reserved.</p>
      </div>

    </div> <!-- /container -->
  </body>
</html>
