<?php
	if (!isset($_GET['error_code'])){ 
		/* handler if the page is opened unintentionally */
		include_once "templates/head_tag.php"; // to get the local url;
		header('Location:'.$localurl."profile.php");
	} 
	else {
		/* if there is a valid error code */
		/* Display the error content based on error code */
		switch ($_GET['error_code']){
			case 0:
				break;
			default:
				break;
		}
	}
?>
<?php
	include_once ("templates/head_tag.php");
?>
<div class="col-sm-6 col-sm-offset-3">
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h2 class="panel-title">Error</h2>
		</div>
		<div class="panel-body">
			<p>Error!!!!!</p>
		</div>
	</div>
</div>
<?php 
	include("templates/footer_tag.php");
?>