<?php
//phpinfo();

/* URL settings */
$localurl= "http://".$_SERVER["HTTP_HOST"]."/fern_wcs/";
//$localurl= "http://www.williamcheng-son.com/customer_portal/";
//$localurl= "http://www.williamcheng-son.com/staging_test/";

/* Email settings */
$cs_email = "david@fern.com.hk";
//$cs_email = "appointment@williamcheng-son.com";
//$test_cust_email ="david@fern.com.hk";
$from_email_booking = "appointment@williamcheng-son.com";
$from_email_system = "appointment@williamcheng-son.com";
$replyto_email = "appointment@williamcheng-son.com";

/* Log File settings */
$email_logfile = $localurl."email_log.csv";
?>