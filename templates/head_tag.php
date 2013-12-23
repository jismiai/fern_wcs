<?php include_once ($_SERVER["DOCUMENT_ROOT"]."/fern_wcs/config.php"); ?>
<!DOCTYPE HTML>
<html>
<head>
	<link href="<?php echo $localurl; ?>css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $localurl; ?>css/wcs_style.css" rel="stylesheet">
	<script type="text/javascript" src="<?php echo $localurl; ?>lib/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="<?php echo $localurl; ?>lib/bootstrap.min.js"></script>
	<?php
		echo $custom_head; //Page-specific head script from other pages
	?>
	<title>
	William Cheng & Son - Customer Profile - 
	<?php echo $custom_title; //Page-specific heading from that page?>
	</title>
</head>
<body>
<div class="container">

<div class="header" style="text-align:center; margin-top:40px;margin-bottom:40px;width:100%;height:91px;background-repeat:no-repeat;background-position:50% 50%;background-image:url('<?php echo $localurl; ?>img/heading.jpg');">
</div>