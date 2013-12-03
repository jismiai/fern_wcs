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
			case 'login_pass_fault':
				$err_title = 'Login : Invalid email';
				$err_message = 'You have entered an invalid email address or password. Please try again.';
				break;
			case 'login_login_fault':
				$err_title = 'Login : Invalid password';
				$err_message = 'You have entered an invalid email address or account number. Please try again.';
				break;
			case 'setpwd_pass_fault':
				$err_title = 'Change Password : Error';
				$err_message = 'You have entered an invalid email address or password. Please try again.';
				break;
			case 'setpwd_pass_notmatch':
				$err_title = 'Change Password : Error';
				$err_message = 'You have entered an invalid email address or account number. Please try again.';
				break;
			case 'register_unique_id':
				$err_title = 'Register error';
				$err_message = 'A customer record with this ID already exists. You must enter a unique customer ID for each record you create.';
				break;
			default:
				$err_title = 'Error';
				$err_message = 'An error has occured. Please make sure you fill correct information in the forms.';
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
			<h2 class="panel-title"><?php echo $err_title;?></h2>
		</div>
		<div class="panel-body">
			<p><?php echo $err_message;?></p>
			<p><a href="javascript: history.go(-1)">Go Back</a></p>
		</div>
	</div>
</div>
<?php 
	include("templates/footer_tag.php");
?>