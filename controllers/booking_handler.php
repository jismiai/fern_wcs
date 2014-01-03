<?php
require_once 'log_control.php';
require_once '../php_functions.php'; // to manipulate datetime data-type in Netsuite
require_once 'email_functions.php';
require_once '../config.php';
require_once '../netsuite_functions.php';

/*read form-input */

//process purposes and produce a string separated by comma.
$purpose=$_POST["purpose_purchase"].", ".$_POST["purpose_alter"].", ".$_POST["purpose_other"];
$purpose=trim($purpose,", ");

//process purchase item multi-selection list
$purchase_item = "";
if (isset($_POST['purpose_purchase']) && strlen($_POST['purpose_purchase'])){
	foreach($_POST['purchase_item'] as $item){
		$purchase_item .= $item.", ";
	}
	$purchase_item = trim($purchase_item,", ");
}
//process alter item multi-selection list
$alter_item = "";
if (isset($_POST['purpose_alter']) && strlen($_POST['purpose_alter'])){
	foreach($_POST['alter_item'] as $item){
		$alter_item .= $item.", ";
	}
	$alter_item = trim($alter_item, ", ");
}

/* prepare mail variable detail */
$email_detail = array();
$email_detail["firstname"] = $_SESSION["firstname"];
$email_detail["lastname"] = $_SESSION["lastname"];
$email_detail["appoint_date"] = $_POST["appoint_date"];

$temp_timeconfig = timeFunctions();
$temp_interval = $temp_timeconfig['timeinterval'];
$temp_datetime = DateTime::createFromFormat('d/m/Y H:i',$_POST["appoint_date"]." ".$_POST["appoint_time"]);
$temp_time = $temp_datetime->format('G:i')."-";
$temp_datetime->add($temp_timeconfig['timeinterval']); // compute next interval now
$temp_time .= $temp_datetime->format('G:i');
$email_detail["appoint_time"] = $temp_time;

$temp_address_by_date = explodeToArray($temp_timeconfig["address_by_date"],";",",",1);
$temp_addresses = explodeToArray($temp_timeconfig["address_str"],"|",";",2);
if (array_key_exists($_POST["appoint_date"],$temp_address_by_date)){
	$email_detail["venue"] = $temp_addresses[$temp_address_by_date[$_POST["appoint_date"]]];
} else {
	$email_detail["venue"] = $temp_timeconfig["event_default_address"]["long"];
}

/*determine if this record should be deleted*/
if (isset($_GET["delete"]) && $_GET["delete"] == "yes"){
	
	//obtain booking id
	$booking_records = searchBookingByEventAndCustomer($_POST["event_id"],$_SESSION['customerID']);
	$delete_booking_id = $booking_records->recordList->record[0]->internalId;
	
	//generate CustomRecord Object
	$custRec = new CustomRecord();
	$recType = new RecordRef();
	$recType->type = 'customRecord';
	$recType->internalId = "118"; //118 stands for travel booking;
	$custRec->recType = $recType;
	$custRec->isInactive = true;
	$custRec->internalId = $delete_booking_id;
	
	//Update request
	$service = new NetSuiteService();
	$request = new UpdateRequest();
	$request->record = $custRec;
	$updateResponse = $service->update($request);
	if (!$updateResponse->writeResponse->status->isSuccess){
		//echo "delete error";
		$err_code = "booking_error";
		header('location:'.$localurl."error.php?error_code=".$err_code);
	} else {
		//send an email to CS
		send_booking_cs_email($cs_email,getCustRec("80", $_POST["event_id"])->name,getCustRec("118", $delete_booking_id)->name, $_SESSION["entityID"],"deleted");
		delete_booking_customer_email($_SESSION["email"],getCustRec("80", $_POST["event_id"])->name,getCustRec("118", $delete_booking_id)->name,$email_detail,"cancelled");
		$success_code = 'booking';
		header('location:'.$localurl."success.php?source=".$success_code);
	}
	exit();
}
/*if not detele, determine add/edit mode to generate add/update request*/

require_once '../netsuite_functions.php';
$booking_records = searchBookingByEventAndCustomer($_POST["event_id"],$_SESSION['customerID']);
//echo "<pre>";
//print_r($booking_records->recordList->record[0]);
//echo "</pre>";
if($booking_records->totalRecords == 0){
	//echo "Add Mode";
} else {
	//echo "Edit Mode";
	$update_booking_id = $booking_records->recordList->record[0]->internalId;
	//echo "booking internal id = ".$update_booking_id;
	
}

/*perform search again to determine whether this slot is still available */

$vacancy_result = savedSearchVacancy($_POST["event_id"],$_POST["appoint_date"]);
$records = $vacancy_result->searchRowList->searchRow;
$vacancy_array = array();
if ($vacancy_result->totalRecords > 0){
	foreach ($records as $record){
		foreach($record->basic->customFieldList->customField as $field){
			if ($field->scriptId == 'custrecord_booking_time'){
				if (!array_key_exists($field->searchValue, $vacancy_array)){
					$vacancy_array[$field->searchValue] = 1;
				} else {
					$vacancy_array[$field->searchValue] +=1;
				}
			}
		}
	}
}
if ($vacancy_array[$_POST["appoint_time"]] > 5){ // hardcode to 5 this time
	$err_code = "booking_slot_full";
	header('location:'.$localurl."error.php?error_code=".$err_code);
}
/*Prepare Web Service record */
$custRec = new CustomRecord();
$recType = new RecordRef();
$recType->type = 'customRecord';
$recType->internalId = "118"; //118 stands for travel booking;
$custRec->recType = $recType;
$custRec->customFieldList = new CustomFieldList();
$custom_field_array = array();

$rec_travel_id = new SelectCustomFieldRef();
$rec_travel_id->scriptId = 'custrecord_booking_travelid';
$rec_travel_id->value = new ListOrRecordRef();
$rec_travel_id->value->typeId = "80"; //80 is travel event;
$rec_travel_id->value->internalId = $_POST["event_id"];

$rec_customer_id = new SelectCustomFieldRef();
$rec_customer_id->scriptId = 'custrecord_booking_custid';
$rec_customer_id->value = new ListOrRecordRef();
$rec_customer_id->value->typeId = "-2"; //-2 is customer;
$rec_customer_id->value->internalId = $_SESSION["customerID"];

$rec_appoint_date = new DateCustomFieldRef();
$rec_appoint_date->scriptId = 'custrecord_booking_date';
$rec_appoint_date->value = datePHPtoNetsuite($_POST["appoint_date"]);

$rec_appoint_time = new StringCustomFieldRef();
$rec_appoint_time->scriptId = 'custrecord_booking_time';
$rec_appoint_time->value = $_POST["appoint_time"];

$rec_purpose = new StringCustomFieldRef();
$rec_purpose->scriptId = 'custrecord_booking_purpose';
$rec_purpose->value = $purpose;

$rec_purchase_item = new StringCustomFieldRef();
$rec_purchase_item->scriptId = 'custrecord_booking_purchase_item';
$rec_purchase_item->value = $purchase_item;

$rec_alter_item = new StringCustomFieldRef();
$rec_alter_item->scriptId = 'custrecord_booking_alter_item';
$rec_alter_item->value = $alter_item;

$rec_otherdetails = new StringCustomFieldRef();
$rec_otherdetails->scriptId = 'custrecord_booking_otherdetails';
$rec_otherdetails->value = $_POST["otherdetails"];

$rec_ch_measure = new StringCustomFieldRef();
$rec_ch_measure->scriptId = 'custrecord_booking_ch_measure';
$rec_ch_measure->value = $_POST["ch_measure"];

$rec_num_companion = new StringCustomFieldRef();
$rec_num_companion->scriptId = 'custrecord_booking_num_companion';
$rec_num_companion->value = $_POST["num_companion"] ;

$rec_name_companion = new StringCustomFieldRef();
$rec_name_companion->scriptId = 'custrecord_booking_name_companion';
$rec_name_companion->value = $_POST["name_companion"];

$rec_refer = new StringCustomFieldRef();
$rec_refer->scriptId = 'custrecord_booking_refer';
$rec_refer->value = $_POST["refer"];

$rec_message = new StringCustomFieldRef();
$rec_message->scriptId = 'custrecord_booking_message';
$rec_message->value = $_POST["message"];

/*$rec_ = new StringCustomFieldRef();
$rec_->scriptId = '';
$rec_->value = ;*/
array_push($custom_field_array,$rec_travel_id,$rec_customer_id,$rec_appoint_date,$rec_appoint_time,$rec_purpose,$rec_purchase_item,$rec_alter_item,$rec_otherdetails,$rec_ch_measure,$rec_num_companion,$rec_name_companion,$rec_refer,$rec_message);

$custRec->customFieldList->customField = $custom_field_array;

/*Prepare Web Service Request (Add/Update) */
$service = new NetSuiteService();

if($booking_records->totalRecords == 0){ // no existing booking for this customer
	$request = new AddRequest(); //new AddRequest
	$request->record = $custRec;
	$addResponse = $service->add($request);
	if (!$addResponse->writeResponse->status->isSuccess){
		echo "add error";
		$err_code = "booking_error";
		header('location:'.$localurl."error.php?error_code=".$err_code);
	} else {
		echo "add success";
		//send an email to CS & customer
		send_booking_cs_email($cs_email,getCustRec("80", $_POST["event_id"])->name,getCustRec("118", $addResponse->writeResponse->baseRef->internalId)->name,$_SESSION["entityID"],"confirmed");
		booking_customer_email($_SESSION["email"],getCustRec("80", $_POST["event_id"])->name,getCustRec("118", $addResponse->writeResponse->baseRef->internalId)->name,$email_detail,"confirmed");
		$success_code = 'booking';
		header('location:'.$localurl."success.php?source=".$success_code);
	}
	echo "<pre>";
	print_r($addResponse->writeResponse);
	echo "</pre>";
} else { // an booking already exists
	$request = new UpdateRequest();
	$custRec->internalId = $update_booking_id;
	$request->record = $custRec;	
	$updateResponse = $service->update($request);
	if (!$updateResponse->writeResponse->status->isSuccess){
		//echo "edit error";
		$err_code = "booking_error";
		header('location:'.$localurl."error.php?error_code=".$err_code);
	} else {
		//send an email to CS & customer
		/*echo "<pre>";
		echo "booking name = ".getCustRec("118", $update_booking_id)->name."<br />";
		echo "event name = ".getCustRec("80", $_POST["event_id"])->name."<br />";
		echo "customer name = ".$_SESSION["entityID"]."<br />";
		echo "</pre>";*/
		send_booking_cs_email($cs_email,getCustRec("80", $_POST["event_id"])->name,getCustRec("118", $update_booking_id)->name,$_SESSION["entityID"],"amended");
		booking_customer_email($_SESSION["email"],getCustRec("80", $_POST["event_id"])->name,getCustRec("118", $update_booking_id)->name,$email_detail,"amended");
		$success_code = 'booking';
		header('location:'.$localurl."success.php?source=".$success_code);
	}
	echo "<pre>";
	print_r($updateResponse);
	echo "</pre>";
}


$custom_head="";
$custom_title="- Your Booking";
include("../templates/head_tag.php");
?>

<?php //output POST results
echo "event ID = ". $_POST["event_id"]."<br />";
echo "Customer ID = ". $_SESSION["customerID"]."<br />";
echo "date =". $_POST["appoint_date"]."<br />";
echo "time =". $_POST["appoint_time"]."<br />";
echo "purpose =".$purpose."<br />";
echo "purchase item = ".$purchase_item."<br />";
echo "alter item = ".$alter_item."<br />";
echo "otherdetails = ".$_POST["otherdetails"]."<br />";
echo "ch_measure= ".$_POST["ch_measure"]."<br />";
echo "num_companion = ".$_POST["num_companion"]."<br />";
echo "name_companion = ".$_POST["name_companion"]."<br />";
echo "refer = ".$_POST["refer"]."<br />";
echo "message = ".$_POST["message"]."<br />";



echo "".$_POST[""]."<br />";
?>

<?php 
require_once '../templates/footer_tag.php';
?>