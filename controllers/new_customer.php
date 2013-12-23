<?php
	//This page connects to Netsuite and create a new customer, using the form in register.php
include_once "../config.php";
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
		/* other useful functions for Netsuite-PHP integration */
		require_once("../netsuite_functions.php");
		/* end of useful functions */
		$genderlist = getCustomListToArray(107); // from netsuite_functions
		/* convert user-inputed salutations into Gender consistent to Netsuite list */
		if (isset($_POST["salutation"])){
			switch($_POST["salutation"]){
				case "Ms.":
				case "Mrs.":
					$gender="F";
					break;
				case "Mr.":
				default:
					$gender="M";
			}
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
	//Custom Fields
	$genderField = new SelectCustomFieldRef();
	$genderField->scriptId = 'custentitymorf';
	$genderField->value = new ListOrRecordRef();
	$genderField->value->internalId = array_search($gender,$genderlist);
	//Setup Main Address
	$address = new CustomerAddressbook();
	$country = new Country();
	$address->defaultBilling = true;
	$address->defaultShipping = form_checkbox_ischecked('sameshipping');
	$address->isResidential = form_checkbox_ischecked('isresidential');
	$address->label = "Main Address";
	$address->addr1 = $_POST["address1"];
	$address->addr2 = $_POST["address2"];
	$address->city =$_POST["city"];
	$address->state = $_POST["state"];
	$address->zip = $_POST["zip"];
	$address->country = $_POST["country"];
	
	//Setup Alternative Address
	if (!form_checkbox_ischecked('sameshipping')){
		$r_address = new CustomerAddressbook();
		$country = new Country();
		$r_address->defaultBilling = false;
		$r_address->defaultShipping = true;
		$r_address->isResidential = form_checkbox_ischecked('r_isresidential');
		$r_address->label = "Alternative Address";
		$r_address->addr1 = $_POST["r_address1"];
		$r_address->addr2 = $_POST["r_address2"];
		$r_address->city =$_POST["r_city"];
		$r_address->state = $_POST["r_state"];
		$r_address->zip = $_POST["r_zip"];
		$r_address->country = $_POST["r_country"];
	}
	/* prepare and assignmenton addressbookList */
	$addresses = array();
	$addresses[0] = $address; //add billing address to addresslist
	if (!form_checkbox_ischecked('sameshipping')){
		$addresses[1] = $r_address; //add shipping address if there is two addresses
	}
	$addressList = new CustomerAddressbookList();
	$addressList->addressbook = $addresses;
	$customer->addressbookList = $addressList;
	
	//new AddRequest
	$request = new AddRequest();
	$request->record = $customer;
	
	//send request and get response
	//$addResponse = $service->add($request);
	
	
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
		header('location:'.$localurl."success.php?source=".$success_code);
	}
}
?>
<div><?php echo $systemMsg; ?></div>
<?php 
include_once "../templates/footer_tag.php";
?>	