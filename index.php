<?php
	include("templates/head_tag.php");
?>
<div>Are you a new customer or old customer?</div>
<form action="login.php" method="post">
	<input type="radio" id="old_customer" value="old_customer" name="new_old" />
	<label for="old_customer">Old Customer</label>
	<br />
	<input type="radio" id="new_customer" value="new_customer" name="new_old" />
	<label for="old_customer">New Customer</label>
	<br />
	<input type="submit" value="Submit" />
</form>
<?php 
	include("templates/footer_tag.php");
?>