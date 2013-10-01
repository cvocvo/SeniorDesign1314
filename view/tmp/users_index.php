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
			<li class="active"><a href="#">Virtual Machines</a></li>
			<li><a href="#">Change Password</a></li>
			<li><a href="#">Log Out</a></li>
        </ul>
		<img src="img/logo.png" alt="logo"/>
        <h3 class="text-muted">Wireless Security Lab</h3>
      </div>
	  
	  <div class="row">
		<div class="col-md-6">
			<h4>Instructions</h4>
			<ol>
				<li>Lorem ipsum bacon</li>
				<li>Lorem ipsum bacon</li>
				<li>Lorem ipsum bacon</li>
				<li>Lorem ipsum bacon</li>
				<li>Lorem ipsum bacon</li>
				<li>Lorem ipsum bacon</li>
				<li>Lorem ipsum bacon</li>
				<li>Lorem ipsum bacon</li>
			</ol>
		</div>
	  </div>

      <div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-green clearPadding">
			<span class="glyphicon glyphicon-flash palette-white statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin"><?=$data['Machine_Name0,Machine_status0'];?></h3>
			<p class="padT10"><strong>IP Address:</strong> 129.186.234.191</p>
			<p class="padT10"><strong>Time Remaining:</strong> 47 minutes</p>
			<div class="padT10">
				<a href="#" class="btn btn-warning"><span class="glyphicon glyphicon-save"></span> Power Down</a>
				<a href="#" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete VM</a>
				<a href="#" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Renew VM Time Remaining</a>
			</div>
		</div>
      </div>
	  
	  <div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-medgray clearPadding">
			<span class="glyphicon glyphicon-off palette-darkgray statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin"><?=$data['Machine_Name1,Machine_status1'];?></h3>
			<p class="padT10">This machine is currently offline.</p>
			<div class="padT10">
				<a href="#" class="btn btn-success"><span class="glyphicon glyphicon-flash"></span> Power On</a>
				<a href="#" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete VM</a>
			</div>
		</div>
      </div>
	  
	  <div class="jumbotron lessPad row">
		<div class="col-md-1 statusPill palette-BC-blue clearPadding">
			<span class="glyphicon glyphicon-export palette-white statusPillIcon"></span>
		</div>
		<div class="col-md-11">
			<h3 class="clearMargin"><?=$data['Machine_Name2,Machine_status2'];?></h3>
			<p class="padT10">This virtual machine has not yet been deployed.</p>
			<div class="padT10">
				<a href="#" class="btn btn-success"><span class="glyphicon glyphicon-export"></span> Deploy Virtual Machine</a>
			</div>
		</div>
      </div>
	  
      <div class="footer">
        <p>&copy; 2013 Iowa State University and/or DEC13-14. All Rights Reserved.</p>
      </div>

    </div> <!-- /container -->
  </body>
</html>
