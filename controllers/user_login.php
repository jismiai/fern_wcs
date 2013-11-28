<?php
require_once '../PHPToolkit/NetSuiteService.php';

$service = new NetSuiteService();

$passport = new LoginRequest();
$passport->passport->account = $service->passport->account;
$passport->passport->email = $_POST["user_email"];
$passport->passport->password = $_POST["user_password"];

//Debug only
//echo $passport->passport->email."<br />";
//echo $passport->passport->password."<br />";

try {
	//send LoginRequest
	$result = $service->login($passport);
	$status = $result->sessionResponse->status;
}
catch(SoapFault $fault)
{
	$systemMsg = "fault string:".$fault->faultstring."<br />";
	$systemMsg .= '<a href="../login.php">Go Back</a>';

	include_once "../templates/head_tag.php";
}
if (isset($status))
	if($status->isSuccess == true){
		//use ID to search customer record
		
		$getrequest = new GetRequest();
		$getrequest->baseRef = new RecordRef();
		$getrequest->baseRef->internalId = $result->sessionResponse->userId->internalId;
		$getrequest->baseRef->type = "customer";
		$getResponse = $service->get($getrequest);
		$customer = $getResponse->readResponse->record;
		/*
		 * echo "<br />Hello! ".$customer->companyName;
		echo '<br />Go to <a href="portal.php">Customer Portal</a>';
		*/
		//Begin session management
		session_start();
		$_SESSION["isLogin"] = true;
		$_SESSION["company"] = $customer->companyName;
		$_SESSION["email"] = $customer->email;
		$_SESSION["internalid"] = $customer->internalId;
		
		include_once "../templates/head_tag.php";
		
		header('location:'.$localurl."portal.php");
	}
?>
<div><?php echo $systemMsg; ?></div>
