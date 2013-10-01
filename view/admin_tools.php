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
			<li><a href="<?print(SITE_ROOT . '/index.php?admin_class_manager');?>">Class Manager</a></li>
			<li class="active"><a href="<?print(SITE_ROOT . '/index.php?admin_tools');?>">Admin Tools</a></li>
			<li><a href="<?print(SITE_ROOT . '/index.php?logout');?>">Log Out</a></li>
        </ul>
		<img src="img/logo.png" alt="logo"/>
        <h3 class="text-muted">Wireless Security Lab</h3>
      </div>
	  
	  <div class="jumbotron row padT20">
		<div class="col-md-12">
			<h2>Edit My Details</h2>
			<form>
				<p>*Only fields with information in them will be changed.</p>
				<div class="form-group">
					<p>Name:</p>
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
				<button type="submit" class="btn btn-success">Save Changes</button>
			</form>
		</div>
      </div>
	  
	  <div class="jumbotron row">
		<div class="col-md-12">
			<h2>Admins Manager</h2>
			<p>Current Admin Users:</p>
			<ul id="classManagerList" class="list-unstyled">
				<li><a href="#">Matt Mallet</a><a href="#" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-remove"></span> Remove From Admins</a></li>
				<li><a href="#">Chris Van Oort (Me)</a></li>
			</ul>
			<p><strong>Note:</strong> By clicking 'Remove From Admins' you will remove all user access to the administrative view. You do not have the ability to remove yourself from the admins group.</p>
			
			<h2>Add Existing User to Admins</h2>
			<form role="form">
				<div class="form-group">
					<select class="form-control">
						<option>Khan, Tahsin</option>
						<option>Niu, Frank</option>
						<option>Tran, Dustin</option>
						<option>Wang, Yuqi</option>
					</select>
				</div>
				<button type="submit" class="btn btn-success">Add user to Administrators Group</button>
			</form>
		</div>
      </div>
	  
	  <div class="jumbotron row padT20">
		<div class="col-md-12">
			<h2>Add New Admin</h2>
			<h4>Add a new user as an Administrator:</h4>
			<form role="form">
			    <div class="form-group">
				    <p>Name:</p>
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
				<button type="submit" class="btn btn-success">Create New Administrator</button>
			</form>
		</div>
      </div>

      <div class="footer">
        <p>&copy; 2013 Iowa State University and/or DEC13-14. All Rights Reserved.</p>
      </div>

    </div> <!-- /container -->
  </body>
</html>
