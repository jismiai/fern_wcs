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
		function form_radio_isvalue($source,$value){
			//$source is $POST/$GET radio box
			//$value the value stored in the checked radio box.
			//return a boolean
			if(isset($_POST[$source])){
				if ($_POST[$source] == $value){
					return true;
				}
			}
			if (isset($_GET[$source])){
				if ($_GET[$source] == $value){
					return true;
				}
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
	$address->defaultBilling = form_radio_isvalue('defaultbilling','address');
	$address->defaultShipping = form_radio_isvalue('defaultshipping','address');
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
	$r_address->defaultBilling = form_radio_isvalue('defaultbilling','r_address');
	$r_address->defaultShipping = form_radio_isvalue('defaultshipping','r_address');
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
		//addResponse status is not success
		$systemMsg = "fault string:".$addResponse->writeResponse->status->statusDetail[0]->code."<br />";
		$systemMsg .= '<a href="javascript: history.go(-1)">Go Back</a>';
		if ($addResponse->writeResponse->status->statusDetail[0]->code == 'UNIQUE_CUST_ID_REQD'){
			$err_code = 'register_unique_id';
		}
		else{ 
			$err_code = "others"; 
		}
		include_once "../templates/head_tag.php";
		header('location:'.$localurl."error.php?error_code=".$err_code);
	} else { // successfully created a new customer
		echo "Thank you very much for your registration! id " . $addResponse->writeResponse->baseRef->internalId;
		include "./session_setup.php";
		$_SESSION["internalid"] = $addResponse->writeResponse->baseRef->internalId;
		
		$systemMsg = 'Go to <a href="../portal.php">customer Portal</a>';
		
		$success_code = 'register';
		include_once "../templates/head_tag.php";
		header('location:'.$localurl."success.php?source=".$success_code);
	}
}
?>
<div><?php echo $systemMsg; ?></div>
<?php 
include_once "../templates/footer_tag.php";
?>	