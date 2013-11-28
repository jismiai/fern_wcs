
<?php
	require_once 'controllers/log_control.php';
	include("templates/head_tag.php");
?>
<h2>Change Password</h2>
<div>Please enter your details below:</div>
<form action="controllers/setpwd.php" method="post">
	<label for="user_password">Current password:</label>
	<input type="password" name="user_password" id="user_password" />
	<br />
	<label for="new_password">New password:</label>
	<input type="password" name="new_password" id="new_password" />
	<br />
	<label for="new_password2">Confirm new password:</label>
	<input type="password" name="new_password2" id="new_password2" />
	<br />
	<input type="submit" value="Submit" />
	<br /><a href="portal.php">Back</a>
</form>
<?php 
	include("templates/footer_tag.php");
?>