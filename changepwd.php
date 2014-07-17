<?php
	/* This file provide the user interface. User input their email here.
	*  Developer customize this page if they plan to accept more input from user */
	require_once 'controllers/log_control.php';
	include("templates/head_tag.php");
?>
<div class="col-sm-10 col-sm-offset-1">
	<h2>Change Password</h2>
	<p>Please enter your details below:</p>
</div>
<form class="form-horizontal" id="wcsform" action="controllers/setpwd.php" method="post">
	<div class="form-group">
	<label for="user_password" class="col-sm-4 control-label">Current password:</label>
		<div class="col-sm-8">
			<input type="password" name="user_password" class="form-control" id="user_password" />
		</div>
	</div>
	<div class="form-group">
	<label for="new_password" class="col-sm-4 control-label">New password:</label>
		<div class="col-sm-8">
			<input type="password" name="new_password" class="form-control" id="new_password" />
		</div>
	</div>
	<div class="form-group">
	<label for="new_password2" class="col-sm-4 control-label">Confirm new password:</label>
		<div class="col-sm-8">
			<input type="password" name="new_password2" class="form-control" id="new_password2" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-4">
			<input type="submit" value="Submit" class="btn btn-wcs-default" />
			<a href="portal.php" class="btn btn-link" >Back</a>
		</div>
	</div>
</form>
<?php 
	include("templates/footer_tag.php");
?>