<?php
require_once 'log_control.php';
include_once "../config.php"; // to get the local url;

if (isset($_POST["so_internalid"])){
	//echo $_POST["so_internalid"]."<br />";
	//call suitelet
	$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=244&deploy=1';
	$postContent = array("custid" => $_SESSION["customerID"], "so_internalid" => $_POST["so_internalid"]);
	//var_dump($postContent);
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/plain','Authorization: NLAuth nlauth_account=3716988,nlauth_email=davidwcs@fern.com.hk,nlauth_signature=willwit78,nlauth_role=3'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK
	$response = curl_exec($ch);

	var_dump($response);
	//JSON response is decoded and saved in $invoices
	//$response = json_decode($response);
	//var_dump($response);
	//output if success
	/*if ($response->isSuccess){
		echo "Successfully received";
		header('Location:'.$localurl."orderstatus.php&success=true");
	} else {
		echo "Error received. error code: ".$response->err_code;
	}*/
	//output if error
		
} else {
	//header('Location:'.$localurl."error.php&error_code=others");
}

?>