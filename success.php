<?php
	if (!isset($_GET['source'])){ 
		/* handler if the page is opened unintentionally */
		include_once "templates/head_tag.php"; // to get the local url;
		header('Location:'.$localurl."profile.php");
	} 
	else {
		/* if there is a valid error code */
		/* Display the error content based on error code */
		switch ($_GET['source']){
			case 'setpwd':
				$message='Passoword is changed.';
				$link='portal.php';
				$link_display='Portal';
				break;
			case 'register':
				$message='Thank you very much for your registration! You can go to portal using the link below.';
				$link='portal.php';
				$link_display='Portal';
				break;
			case 'profile':
				$message='Your profile is updated. Return to portal page using the link below.';
				$link='portal.php';
				$link_display='Portal';
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
	<div class="panel panel-success">
		<div class="panel-heading">
			<h2 class="panel-title">Success</h2>
		</div>
		<div class="panel-body">
			<p><?php echo $message; ?></p>
			<p><a href="<?php echo $link; ?>"><?php echo $link_display; ?></a></p>
		</div>
	</div>
</div>
<?php 
	include("templates/footer_tag.php");
?>