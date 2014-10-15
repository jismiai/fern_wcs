<?php
//phpinfo();
$localurl= "http://".$_SERVER["HTTP_HOST"]."/fern_wcs/";
$documentroot = $_SERVER['DOCUMENT_ROOT']."/fern_wcs/";
//$documentroot = $_SERVER['DOCUMENT_ROOT'];
//$localurl= "https://www.williamcheng-son.com/customer_portal/";
//$localurl= "https://www.williamcheng-son.com/staging_test/";
//$cs_email = "david@fern.com.hk";
$cs_email = "appointment@williamcheng-son.com";
//$test_cust_email ="david@fern.com.hk";
$from_email_booking = "appointment@williamcheng-son.com";
$from_email_system = "appointment@williamcheng-son.com";
$replyto_email = "appointment@williamcheng-son.com";

if ($_SERVER["HTTPS"] != "on" || !isset($_SERVER["HTTPS"])){
	//header('location:'.$localurl.$_SERVER['REQUEST_URI']);
	//header('location:'."https://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']);
}

?>