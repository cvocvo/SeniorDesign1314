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
			  <li><a href="<?print(SITE_ROOT . '/index.php?admin_class_manager');?>">Class Manager</a></li>
			  <li class="active"><?=$data['class'];?></li>
			</ol>
			<h4>Currently Viewing: <?=$data['class'];?></h4>
		</div>
	  </div>
	  
	  <div class="jumbotron row padT20">
		<div class="col-md-12">
			<h2>Class Management Toolbox</h2>
			<p>All of these actions should be taken with extreme care as they will affect all students in the class.</p>
			<h4 class="padT20">Change Class Attributes:</h4>
			<form action="index.php" method="post" role="form">
				<input type="hidden" name="page" value="admin_class_view" />
				<input type="hidden" name="class" value="<?=$data['class'];?>" />
				<input type="hidden" name="form_id" value="class"/>
				<!--<div class="form-group">
					<p>Class Name:</p>
					<input type="text" class="form-control" name="classname" placeholder="Enter your class name">
				</div>-->
				<p>Select Virtual Machines to grant class access to:</p>
				<?php
				foreach ($data['images'] as $image){
					$checked = (in_array($image, $data['class_images'])) ? 
						'checked' : '';
					echo '<div class="checkbox"><label>
					<input type="checkbox" name="' . $image . '" ' . $checked . '>
					' . $image . '</label>
					</div>';
				}
				?>
				<button type="submit" name="action" value="save" class="btn btn-success">Save</button>	
			<br>
			<button type="submit" name="action" value="renew" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>Renew VMs</button>
			<br><br>
			<button type="submit" name="action" value="power_down_vms" class="btn btn-warning"><span class="glyphicon glyphicon-save"></span>Power Down VMs</button>
			<br><br>
			<button type="submit" name="action" value="delete_vms" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>Delete VMs</button>
			</form>
		</div>
      </div>

	  <div class="jumbotron row padT20">
		<div class="col-md-12">
			<h2>Students</h2>
			<p>Looking to change a username/name/password/reset a VM for a student? Click the student's name below:</p>
			<form action="index.php" method="post" role="form">
				<input type="hidden" name="page" value="admin_class_view" />
				<input type="hidden" name="form_id" value="students"/>
				<input type="hidden" name="class" value="<?=$data['class'];?>" />
				<ul class="list-unstyled">
				<?php
				foreach ($data['students'] as $student_name => $student_info){
					$disabled = ($student_name == $data['user']) ? 'disabled' : '';
					echo '<li>
						<a href="' . SITE_ROOT . '/index.php?admin_student_view&student=' . $student_name . '">' . $student_name . '</a>
						<button type="submit" name="student" value="' . $student_name . '" class="btn btn-danger pull-right" ' . $disabled . '><span class="glyphicon glyphicon-remove"></span>Delete Student</button>	
					</li>
					';
				}
				?>
				</ul>
			</form>
		</div>
      </div>

      <div class="footer">
        <p>&copy; 2013 Iowa State University and/or DEC13-14. All Rights Reserved.</p>
      </div>

    </div> <!-- /container -->
  </body>
</html>
