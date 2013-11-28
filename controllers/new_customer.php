<?php
	//This page connects to Netsuite and create a new customer, using the form in register.php
include_once "../templates/head_tag.php";
?>
<?php
if (!isset($_POST["source"])){ // check the flow
	header('Location:'.$localurl."register.php");
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
	$customer->isPerson = true;
	$customer->phone = $_POST["phone"];
	$customer->comments = $_POST["comments"];	
	$customer->email = $_POST["user_email"];
	$customer->password = $_POST["user_password"];
	$customer->password2 = $_POST["user_password"];
	$customer->giveAccess = true;
	$customer->accessRole->internalId = "14";
	//Setup Billing Address
	$address = new CustomerAddressbook();
	$country = new Country();
	$address->defaultBilling = true;
	$address->defaultShipping = false;
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
	
	//new AddRequest
	$request = new AddRequest();
	$request->record = $customer;
	
	//send request and get response
	$addResponse = $service->add($request);
	
	
	if (!$addResponse->writeResponse->status->isSuccess) {
		$systemMsg = "ADD ERROR";
	} else {
		echo "Thank you very much for your registration! id " . $addResponse->writeResponse->baseRef->internalId;
		session_start();
		$_SESSION["isLogin"] = true;
		$_SESSION["company"] = $customer->firstName ." ".$customer->lastName;
		$_SESSION["email"] = $customer->email;
		$_SESSION["internalid"] = $addResponse->writeResponse->baseRef->internalId;
		
		$systemMsg = 'Go to <a href="../portal.php">customer Portal</a>';
	}
}
?>
<div><?php echo $systemMsg; ?></div>
<?php 
include_once "../templates/footer_tag.php";
?>	