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

//Debug only
/*echo $pwdpassport->changePassword->currentPassword."<br />";
echo $pwdpassport->changePassword->newPassword."<br />";
echo $pwdpassport->changePassword->newPassword2."<br />";
echo $pwdpassport->changePassword->justThisAccount."<br />";
echo $service->passport->email."<br />";
echo $service->passport->password."<br />";*/

try {
	//send ChangePasswordRequest
	$response = $service->changePassword($pwdpassport);
	$status = $response->sessionResponse->status;
}
catch(SoapFault $fault)
{
	$systemMsg = "fault string:".$fault->faultstring."<br />";
	$systemMsg .= '<a href="../changepwd.php">Go Back</a>';
}
if (isset($status))
	if ($status->isSuccess){
		$systemMsg = "Passoword is changed. <br />";
		$systemMsg .= '<a href="../portal.php">Back to Portal</a>';
	}
	else {
		//cannot analysis response so 
		$systemMsg = "New passwords don't match.";
		$systemMsg .= '<a href="../changepwd.php">Go Back</a>';
	}
?>
<?php 
	include_once "../templates/head_tag.php";
?>
	<div> <?php echo $systemMsg; ?></div>
<?php 
	include_once "../templates/footer_tag.php";
?>