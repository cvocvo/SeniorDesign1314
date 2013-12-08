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
			<li><a href="<?print(SITE_ROOT . '/index.php?user_index');?>">Virtual Machines</a></li>
			<li class="active"><a href="<?print(SITE_ROOT . '/index.php?user_change_password');?>">Change Password</a></li>
			<li><a href="<?print(SITE_ROOT . '/index.php?logout');?>">Log Out</a></li>
        </ul>
		<img src="img/logo.png" alt="logo"/>
        <h3 class="text-muted">Wireless Security Lab</h3>
      </div>

      <div class="jumbotron row">
		<div class="col-md-10 col-md-offset-1">
			<h2>Change Password</h2>
			<form action="index.php" method="post">
				<p>*All fields required in order to change password</p>
				<input type="hidden" name="page" value="user_change_password"/>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="*Enter Current Password">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="newpassword" placeholder="*Enter NEW Password">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="newpassword2" placeholder="*Enter NEW Password Again">
				</div>
				<button type="submit" class="btn btn-success">Change Password</button>
			</form>
		</div>
      </div>
	  
      <div class="footer">
        <p>&copy; 2013 Iowa State University and/or DEC13-14. All Rights Reserved.</p>
      </div>

    </div> <!-- /container -->
  </body>
</html>
