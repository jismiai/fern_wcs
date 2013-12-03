<?php
//redirect if not login in
require_once 'log_control.php';

require_once '../PHPToolkit/NetSuiteService.php';

$service = new NetSuiteService();

//$passport = new LoginRequest();
//$passport->passport->account = $service->passport->account;
//$passport->passport->email = $_SESSION["email"];;
//$passport->passport->password = $_POST["user_password"];
$service->passport->email = $_SESSION["email"];;
$service->passport->password = $_POST["user_password"];
$service->passport->role->internalId = "14";

$pwdpassport = new ChangePasswordRequest();
$pwdpassport->changePassword->currentPassword = $_POST["user_password"];
$pwdpassport->changePassword->newPassword = $_POST["new_password"];
$pwdpassport->changePassword->newPassword2 = $_POST["new_password2"];
$pwdpassport->changePassword->justThisAccount = true;


try {
	//send ChangePasswordRequest
	$response = $service->changePassword($pwdpassport);
	$status = $response->sessionResponse->status;
}
catch(SoapFault $fault)
{
	$systemMsg = "fault code:".$fault->faultcode."<br />";
	$systemMsg .= "fault string:".$fault->faultstring."<br />";
	$systemMsg .= '<a href="javascript: history.go(-1)">Go Back</a>';
	if (strpos($fault->faultstring,'You have entered an invalid email address or password.') !== false){
		$err_code = 'setpwd_pass_fault';
	}
	else{ 
		$err_code = "others"; 
	}
	include_once "../templates/head_tag.php";
	header('location:'.$localurl."error.php?error_code=".$err_code);
}
	if (isset($status))
	if ($status->isSuccess){
		$systemMsg = "Passoword is changed. <br />";
		$systemMsg .= '<a href="../portal.php">Back to Portal</a>';
		
		$success_code = 'setpwd';
		include_once "../templates/head_tag.php";
		header('location:'.$localurl."success.php?source=".$success_code);
	}
	else {
		//falls here if password doesn't match;
		$systemMsg = "New passwords don't match.";
		$systemMsg .= '<a href="javascript: history.go(-1)">Go Back</a>';
		$err_code = 'setpwd_pass_notmatch';
		include_once "../templates/head_tag.php";
		header('location:'.$localurl."error.php?error_code=".$err_code);
	}
?>
<?php 
	include_once "../templates/head_tag.php";
?>
	<div> <?php echo $systemMsg; ?></div>
<?php 
	include_once "../templates/footer_tag.php";
?>