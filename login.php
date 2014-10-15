
<?php
	include("templates/head_tag.php");
?>
<div class="col-sm-4 col-sm-offset-1" style="display:none;">
	<div class="panel panel-default">
		<div class="panel-heading">Australia Trip Booking is now available.</div>
		<div class="panel-body">
		</div>
	</div>
</div>
<div class="col-sm-6 col-sm-offset-3">
	<h2 style="margin-top:0">Customer Login</h2>
	<p>Please enter your login information below:</p>
	<form class="form-horizontal" action="controllers/user_login.php" method="post">
		<div class="form-group">
			<label for="user_email" class="col-sm-3 control-label">Email:</label>
			<div class="col-sm-9">
				<input type="email" name="user_email" class="form-control" id="user_email" />
			</div>
		</div>
		<div class="form-group">
			<label for="user_password" class="col-sm-3 control-label">Password:</label>
			<div class="col-sm-9">
				<input type="password" name="user_password" class="form-control" id="user_password" />
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-2">
				<button type="submit" value="Login" class="btn btn-wcs-default">Login</button>
			</div>
			<div class="col-sm-3 form-control-static">
				<a href="resetpassword.php">Forget password</a>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				Not yet a customer? <a href="register.php">Register</a> here.
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				Click <a href="mailto:customerservice@williamcheng-son.com">here</a> to send us an email if you have any problems.
			</div>
		</div>
	</form>
</div>
<?php 
	include("templates/footer_tag.php");
?>