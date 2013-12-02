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
	$customer->phone = $_POST["phone"];
	$customer->comments = $_POST["comments"];
	$customer->internalId = $_SESSION["internalid"];	
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
	
	/* Use a request to obtain current list of addresses of this customer */
	$request = new GetRequest();
	$request->baseRef = new RecordRef();
	$request->baseRef->internalId = $_SESSION["internalid"];
	$request->baseRef->type = "customer";
	$getResponse = $service->get($request);
	if (!$getResponse->readResponse->status->isSuccess) {
		echo "GET ERROR";
	} else {
		//assign customer details into $customer
		$customer_read = $getResponse->readResponse->record;
		//assign address details into $address
		$addressBookListArray = $customer_read->addressbookList->addressbook;
	}
	/* end of getting the address list */
	
	$addresses = $addressBookListArray; //
	$addresses[0] = $address; //overwrite main address to addresslist
	$addresses[1] = $r_address; //overwrite alternative address to addresslist
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