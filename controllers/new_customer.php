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
	function form_checkbox_ischecked($source) {
		//$source should be a $POST/$GET checkbox, $output is a boolean
		//resulting boolean will be returned;
		if(isset($_POST[$source]) || isset($_GET[$source])){
			return true;
		}
		return false;
	}
	
	//Create web service request
	require_once '../PHPToolkit/NetSuiteService.php';
	
	$service = new NetSuiteService();
	//Prepare Customer record type from user input
	$customer = new Customer();
	$customer->salutation = $_POST["salutation"];
	$customer->lastName = $_POST["lastname"];
	$customer->firstName = $_POST["firstname"];
	$customer->companyName = $_POST["companyname"];
	$customer->isPerson = true;
	$customer->phone = $_POST["phone"];
	$customer->comments = $_POST["comments"];	
	$customer->email = $_POST["user_email"];
	$customer->password = $_POST["user_password"];
	$customer->password2 = $_POST["user_password"];
	$customer->giveAccess = true;
	$customer->accessRole->internalId = "14";
	//Setup Main Address
	$address = new CustomerAddressbook();
	$country = new Country();
	$address->defaultBilling = form_checkbox_ischecked('defaultbilling');
	$address->defaultShipping = form_checkbox_ischecked('defaultshipping');
	$address->isResidential = form_checkbox_ischecked('isresidential');
	$address->label = "Main Address";
	$address->addr1 = $_POST["address1"];
	$address->addr2 = $_POST["address2"];
	$address->city =$_POST["city"];
	$address->state = $_POST["state"];
	$address->zip = $_POST["zip"];
	$address->country = $_POST["country"];
	
	//Setup Alternative Address
	$r_address = new CustomerAddressbook();
	$country = new Country();
	$r_address->defaultBilling = form_checkbox_ischecked('r_defaultbilling');
	$r_address->defaultShipping = form_checkbox_ischecked('r_defaultshipping');
	$r_address->isResidential = form_checkbox_ischecked('r_isresidential');
	$r_address->label = "Alternative Address";
	$r_address->addr1 = $_POST["r_address1"];
	$r_address->addr2 = $_POST["r_address2"];
	$r_address->city =$_POST["r_city"];
	$r_address->state = $_POST["r_state"];
	$r_address->zip = $_POST["r_zip"];
	$r_address->country = $_POST["r_country"];
	
	/* prepare and assignmenton addressbookList */
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