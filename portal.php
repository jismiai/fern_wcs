<?php 
	//This is the land page of customer centre after login
?>
<?php
	require_once 'controllers/log_control.php';
	include("templates/head_tag.php");
?>
<div>Welcome <?php echo $_SESSION["company"];?></div>
<div>your ID is  <?php echo $_SESSION["internalid"];?></div>
<h2>Customer Portal</h2>
<ul>
	<li><a href="changepwd.php">Change Password</a></li>
	<li><a href="profile.php">Update Profile</a></li>
	<li><a href="controllers/logout.php">Logout</a></li>
</ul>	
<?php 
	include("templates/footer_tag.php");
?>