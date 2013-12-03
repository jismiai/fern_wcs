
<?php
	include("templates/head_tag.php");
?>
<div class="col-sm-10 col-sm-offset-1">
	<h2>Customer Login</h2>
	<p>Please enter your login information below:</p>
</div>
<form class="form-horizontal" action="controllers/user_login.php" method="post">
	<div class="form-group">
		<label for="user_email" class="col-sm-4 control-label">Email:</label>
		<div class="col-sm-8">
			<input type="email" name="user_email" class="form-control" id="user_email" />
		</div>
	</div>
	<div class="form-group">
		<label for="user_password" class="col-sm-4 control-label">Password:</label>
		<div class="col-sm-8">
			<input type="password" name="user_password" class="form-control" id="user_password" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-4">
			<button type="submit" value="Login" class="btn btn-wcs-default">Login</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-4">
			Not yet a customer? <a href="register.php">Register</a> here.
		</div>
	</div>
</form>

<?php 
	include("templates/footer_tag.php");
?>