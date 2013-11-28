
<?php
	include("templates/head_tag.php");
?>
	<div>Please enter your login information below:</div>
	<form action="controllers/user_login.php" method="post">
		<label for="user_email">Email:</label>
		<input type="email" name="user_email" id="user_email" />
		<br />
		<label for="user_password">Password:</label>
		<input type="password" name="user_password" id="user_password" />
		<input type="submit" value="Login" />
	</form>
<?php
	// Redirect the customer to register.php.

		if (isset($_POST["new_old"]) && $_POST["new_old"] == "new_customer"){
			header('Location:'."register.php");
		}
?>


<?php 
	include("templates/footer_tag.php");
?>