<?php
require_once 'controllers/log_control.php';
include_once "config.php"; // to get the local url;
include_once $documentroot."/PHPToolkit/NSconfig.php";
if (isset($_POST["so_internalid"])){
	//echo $_POST["so_internalid"]."<br />";
	//call suitelet
	$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=246&deploy=1';
	$postContent = array("custid" => $_SESSION["customerID"], "so_internalid" => $_POST["so_internalid"]);
	//var_dump($postContent);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json','Authorization: NLAuth nlauth_account='.$nsaccount.',nlauth_email='.$nsemail.',nlauth_signature='.$nspassword.',nlauth_role='.$nsrole));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postContent));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK
	$response = curl_exec($ch);

	//JSON response is decoded and saved in $invoices
	$response2 = json_decode($response);
	echo"<pre>";
	var_dump($response2);
	echo"</pre>";
	//output if success
	/*if ($response->isSuccess){
		echo "Successfully received";
		header('Location:'.$localurl."orderstatus.php?success=true");
	} else {
		//output if error
		echo "Error received. error code: ".$response->err_code;
	}*/
		
} else {
	//header('Location:'.$localurl."error.php?error_code=others");
}
?>