<?php

/* This page process the user input via website. It then send the HTTP request to Netsuite.
*  It also decode the JSON response from Netsuite and renders output */

//required library files
include_once "../config.php";
include_once $documentroot."/PHPToolkit/NSconfig.php";


/* This section accepts the user input as post request from previous page */
//get the email
if(!isset($_POST['user_email'])){
	header('location:'.$localurl."?error_code=other");
	exit;
}

/* this section prepares the HTTP request to be sent to RestLet of Netsuite.
* The syntax will be different for other languages but the HEADER, POSTFIELDS will be very similar */

//search in Netsuite customer record
$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=262&deploy=1';
$postContent = array("cust_email" => $_POST['user_email']);
echo $_POST['user_email']."<br/>";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json','Authorization: NLAuth nlauth_account='.$nsaccount.',nlauth_email='.$nsemail.',nlauth_signature='.$nspassword.',nlauth_role='.$nsrole));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postContent));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK

/* sent the request, then decodes the JSON response from RestLET to $customer */
$response = curl_exec($ch);
$customer = json_decode($response);
//echo "<pre>"; var_dump($ch);var_dump($response); echo "</pre>";


/* render output depend on response */

//if found, get first time password and set 
//send email
if ($customer->isSuccess === true){
	require_once 'email_functions.php';
	send_reset_password_email($customer->email,$customer->firstname,$customer->lastname,$customer->firstPassword);
	header('location:'.$localurl."success.php?source=resetpwd");
	exit;
}
//if not found, output error and return to last page.
if ($customer->isSuccess === false){
	header('location:'.$localurl."resetpassword.php?error=1"); // not found error.
	exit;
}
//header('location:'.$localurl."portal.php");
?>