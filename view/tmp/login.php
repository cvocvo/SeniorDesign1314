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
        <!--<ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Login</a></li>
        </ul>-->
		<img src="img/logo.png" alt="logo"/>
        <h3 class="text-muted">Wireless Security Lab</h3>
      </div>

      <div class="jumbotron row">
		<div class="col-md-10 col-md-offset-1">
			<h2>Login</h2>
			<form>
				<div class="form-group">
					<input type="email" class="form-control" name="username" placeholder="Enter Username">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Enter Password">
				</div>
				<div class="checkbox">
				<label>
				  <input type="checkbox"> I agree to the <a href="#" target="_blank">Terms and Conditions</a>
				</label>
				</div>
				<button type="submit" class="btn btn-success">Login</button>
				<!-- This needs to be hooked up to the db-->
			</form>
		</div>
      </div>
	  
      <div class="footer">
        <p>&copy; 2013 Iowa State University and/or DEC13-14. All Rights Reserved.</p>
      </div>

    </div> <!-- /container -->
  </body>
</html>
