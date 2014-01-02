
<?php
	include("templates/head_tag.php");
?>
<div class="col-sm-4 col-sm-offset-1">
	<div class="panel panel-default">
		<div class="panel-heading">Australia Trip Booking is now available.</div>
		<div class="panel-body">
			<p>We are travelling to Australia in Feb 2014. Click <a href="event.php">here</a> to see the details of the trip.</p>
			<p>Bookings can be made in this customer portal after login.</p>
			<p>We have also opened <a href="register.php">new registration</a> to our portal for new customers.</p>
		</div>
	</div>
</div>
<div class="col-sm-6">
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
			<div class="col-sm-offset-3 col-sm-4">
				<button type="submit" value="Login" class="btn btn-wcs-default">Login</button>
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