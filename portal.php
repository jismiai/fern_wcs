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
		<li class="list-group-item"><a href="orderstatus.php">Order Status</a></li>
		<li class="list-group-item"><a href="shippingstatus.php">Shipping Status</a></li>
		<li class="list-group-item"><a href="orderhistory.php">Order History</a></li>
		<li class="list-group-item"><a href="paymentstatus.php">Payment Status</a></li>
		<li class="list-group-item"><a href="case.php">Online Customer Services</a></li>
		<li class="list-group-item"><a href="controllers/logout.php">Logout</a></li>
	</ul>
</div>	
<?php 
	include("templates/footer_tag.php");
?>