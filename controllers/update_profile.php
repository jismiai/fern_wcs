<?php 
	include_once "../templates/head_tag.php";
?>
<?php
require_once 'log_control.php';
if (!isset($_POST["source"])){ // check the flow
	header('Location:'.$localurl."profile.php");
}
else {
	//server-side validation should be done here.
	
	//Create web service request
	require_once '../PHPToolkit/NetSuiteService.php';
	
	$service = new NetSuiteService();
	//Prepare Customer record type from user input
	$customer = new Customer();
	$customer->salutation = $_POST["salutation"];
	$customer->lastName = $_POST["lastname"];
	$customer->firstName = $_POST["firstname"];
	$customer->companyName = $_POST["lastname"]." ".$_POST["firstname"];
	$customer->phone = $_POST["phone"];
	$customer->comments = $_POST["comments"];
	$customer->internalId = $_SESSION["internalid"];	
	//Setup Billing Address
	$address = new CustomerAddressbook();
	$country = new Country();
	$address->defaultBilling = true;
	$address->defaultShipping = true;
	$address->label = "Billing Address";
	$address->addr1 = $_POST["address1"];
	$address->addr2 = $_POST["address2"];
	$address->city =$_POST["city"];
	$address->state = $_POST["state"];
	$address->zip = $_POST["zip"];
	$address->country = $_POST["country"];
	
	//Setup Residental Address
	$r_address = new CustomerAddressbook();
	$country = new Country();
	$r_address->defaultBilling = false;
	$r_address->defaultShipping = true;
	$r_address->isResidential = true;
	$r_address->label = "Residental Address";
	$r_address->addr1 = $_POST["r_address1"];
	$r_address->addr2 = $_POST["r_address2"];
	$r_address->city =$_POST["r_city"];
	$r_address->state = $_POST["r_state"];
	$r_address->zip = $_POST["r_zip"];
	$r_address->country = $_POST["r_country"];
	
	$addresses = array();
	$addresses[0] = $address; //add billing address to addresslist
	$addresses[1] = $r_address; //add residental address to addresslist
	$addressList = new CustomerAddressbookList();
	$addressList->addressbook = $addresses;
	$customer->addressbookList = $addressList;

	
	//new UpdateRequest
	$request = new UpdateRequest();
	$request->record = $customer;
	
	//send request and get response
	$writeresponse = $service->update($request);
	
	
	if (!$writeresponse->writeResponse->status->isSuccess) {
		$systemMsg = "ADD ERROR";
	} else {
		$systemMsg = "Update SUCCESS, id " . $writeresponse->writeResponse->baseRef->internalId;
		$systemMsg .= "<br /><a href='../profile.php'>Go to profile</a>";
	}
}
?>
	<div><?php echo $systemMsg; ?></div>
<?php 
	include_once "../templates/footer_tag.php";
?>	