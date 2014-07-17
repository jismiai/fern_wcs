<?php
	include("templates/head_tag.php");
	/* This file provide the user interface. User input their email here.
	*  Developer customize this page if they plan to accept more input from user */
?>


<div class="col-sm-6 col-sm-offset-3">
	<?php if (isset($_GET["error"]) && $_GET["error"]==1){?>
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h2 class="panel-title">Error</h2>
			</div>
			<div class="panel-body">
				<p>The email address you entered doesn't exist.</p>
			</div>
		</div>
	<?php } ?>
	<h2 style="margin-top:0">Reset Password</h2>
	<p>Please enter your email below. We will send the new password to your email.</p>
	<form class="form-horizontal" action="controllers/resetpassword_controller.php" method="post">
		<div class="form-group">
			<label for="user_email" class="col-sm-3 control-label">Email:</label>
			<div class="col-sm-9">
				<input type="email" name="user_email" class="form-control" id="user_email" />
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-2">
				<button type="submit" value="submit" class="btn btn-wcs-default">Submit</button>
			</div>
			<div class="col-sm-3 form-control-static">
				<a href="login.php">Back</a>
			</div>
		</div>
	</form>
<?php 
	include("templates/footer_tag.php");
?>