<?php
	/*Get Request parameters
	 * source : determine the type of message to appear
	 * detail : detail of the message
	 */
	if (!isset($_GET['source'])){ 
		/* handler if the page is opened unintentionally */
		include_once "templates/config.php"; // to get the local url;
		header('Location:'.$localurl."profile.php");
	} 
	else {
		/* if there is a valid error code */
		/* Display the error content based on error code */
		switch ($_GET['source']){
			case 'setpwd':
				$message='Password is changed.';
				$link='portal.php';
				$link_display='Portal';
				break;
			case 'register':
				$message='Thank you very much for your registration! You can login to portal using the link below.';
				$link='login.php';
				$link_display='Login';
				break;
			case 'profile':
				$message='Your profile is updated. Return to portal page using the link below.';
				$link='portal.php';
				$link_display='Portal';
				break;
			case 'booking':
				$message='Your booking has been made/updated. Return to portal page using the link below.';
				$link='portal.php';
				$link_display='Portal';
				break;
			case 'supportcase':
				$message="Your enquiry has been filed as {$_GET["detail"]}. Our customer service staff will look into the case and contact you in short.";
				$link='portal.php';
				$link_display='Portal';
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