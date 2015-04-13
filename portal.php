<?php 
	//This is the land page of customer centre after login
?>
<?php
	require_once 'controllers/log_control.php';
	include("templates/head_tag.php");
?>
<?php 
//echo $_SESSION["company"];
//echo $_SESSION["internalid"];
//echo "billing = ".$_SESSION["billing_country"]."<br />";
//echo "shipping = ".$_SESSION["shipping_country"];
 ?>
<div class="panel panel-default">
	<div class="panel-heading">Customer Portal. Your ID is <?php echo $_SESSION['entityID'];?></div>
	<div class="panel-body">Welcome <?php echo $_SESSION["firstname"];?>! Please choose one of the operations below.</div>
	<ul class="list-group">
		<li class="list-group-item"><a href="changepwd.php">Change Password</a></li>
		<li class="list-group-item"><a href="profile.php">Update Profile</a></li>
		<?php if ($_SESSION['billing_country'] == "Australia" || $_SESSION['shipping_country'] == "Australia"): ?>
		<li class="list-group-item"><a href="event.php">Trip Booking</a></li>
		<?php endif; ?>
		<li class="list-group-item"><a href="controllers/logout.php">Logout</a></li>
	</ul>
</div>	
<?php 
	include("templates/footer_tag.php");
?>