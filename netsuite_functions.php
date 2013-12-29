<?php
require_once 'PHPToolkit/NetSuiteService.php';

function getCustomListToArray($listInternalId){
	/* this functions loads the custom list with internal ID = $listInternalId
	 * it returns an array of the list
	 * whose key is equal to list option's internal ID
	 * value is the list option's value
	 */
	$service = new NetSuiteService();
	$request = new GetRequest();
	$request->baseRef = new RecordRef();
	$request->baseRef->internalId = $listInternalId;
	$request->baseRef->type = "customList";
	$getResponse = $service->get($request);
	$customlist = $getResponse->readResponse->record;
	$output = array();
	foreach ($customlist->customValueList->customValue as $listoption){
		$output[$listoption->valueId] = $listoption->value;
	}
	return $output;
}

function searchEmail($input_email){
	$service = new NetSuiteService();
	//$service->setSearchPreferences(false, 20);
	$emailSearchField = new SearchStringField();
	$emailSearchField->operator = "contains";
	$emailSearchField->searchValue = $input_email;
	
	$search = new CustomerSearchBasic();
	$search->email = $emailSearchField;
	
	$request = new SearchRequest();
	$request->searchRecord = $search;
	
	$searchResponse = $service->search($request);
	
	if (!$searchResponse->searchResult->status->isSuccess) {
		return -1;
	} else {
		return $searchResponse->searchResult->totalRecords;
	}
}
function getCustRec($rectypeId, $recordId){

	$service = new NetSuiteService();
	$request = new GetRequest();
	$request->baseRef = new CustomRecordRef();
	$request->baseRef->internalId = $recordId; // custom record key
	$request->baseRef->typeId = $rectypeId; // id of this custom record type
	$getResponse = $service->get($request);

	$custRec = $getResponse->readResponse->record;
	return $custRec;
}

function searchBookingByEventAndCustomer($eventID,$customerID){
	/* this function search for travel bookings by Event ID and Customer ID
	 * It returns -1 if error occurs, and returns the search result otherwise
	 * The function only include the citeria if the input parameter is not empty.
	 */
	
	$service = new NetSuiteService();
	//prepare searchCustomField object for Travel Event ID
	$listRef = new ListOrRecordRef();
	$listRef->internalId = $eventID;
	$listRef->typeId = "80"; // travel event
	$listSearchField = new SearchMultiSelectCustomField();
	$listSearchField->operator = "anyOf";
	$listSearchField->scriptId = 'custrecord_booking_travelid';
	$listSearchField->searchValue = $listRef;
	
	//prepare searchCustomField object for customer ID
	$listRef2 = new ListOrRecordRef();
	$listRef2->internalId = $customerID;
	$listRef2->typeId = "-2"; // customer
	$listSearchField2 = new SearchMultiSelectCustomField();
	$listSearchField2->operator = "anyOf";
	$listSearchField2->scriptId = 'custrecord_booking_custid';
	$listSearchField2->searchValue = $listRef2;
	
	$customSearchFields = array();
	if (strlen($eventID) > 0)
		array_push($customSearchFields,$listSearchField);
	if (strlen($customerID) > 0)
		array_push($customSearchFields,$listSearchField2);
	
	$is_not_inactive = new SearchBooleanField();
	$is_not_inactive->searchValue = false;
	/*
	echo "<pre>";
	print_r($customSearchFields);
	echo "</pre>";
	*/
	$search = new CustomRecordSearchBasic();
	$search->recType = new RecordRef();
	$search->recType->type = "customRecord";
	$search->recType->internalId = "118"; //118 is internal ID of travel booking
	$search->isInactive = $is_not_inactive; //exclude inactive records
	$search->customFieldList = new SearchCustomFieldList();
	$search->customFieldList->customField = $customSearchFields;
	/*
	echo "<pre>";
	print_r($search);
	echo "</pre>";
	*/
	$request = new SearchRequest();
	$request->searchRecord = $search;

	$searchResponse = $service->search($request);
	/*
	echo "<pre>";
	print_r($searchResponse);
	echo "</pre>";
	*/
	if (!$searchResponse->searchResult->status->isSuccess) {
		return -1;
	} else {
		return $searchResponse->searchResult;
	}
}
function savedSearchVacancy($eventID,$date){
	/* search bookings based on event ID and Date
	 * return search results if there is search result
	 */
	$listRef = new ListOrRecordRef();
	$listRef->internalId = $eventID;
	$listRef->typeId = "80"; // travel event
	$listSearchField = new SearchMultiSelectCustomField();
	$listSearchField->operator = "anyOf";
	$listSearchField->scriptId = 'custrecord_booking_travelid';
	$listSearchField->searchValue = $listRef;


	$customSearchFields = array();
	if (strlen($eventID) > 0)
		array_push($customSearchFields,$listSearchField);
	if (strlen($date) > 0){
		$dateSearchField = new SearchDateCustomField();
		$dateSearchField->searchValue = datePHPtoNetsuite($date);
		$dateSearchField->operator = "on";
		$dateSearchField->scriptId = 'custrecord_booking_date';
		array_push($customSearchFields,$dateSearchField);
	}

	$is_not_inactive = new SearchBooleanField();
	$is_not_inactive->searchValue = false;

	$searchbasic = new CustomRecordSearchBasic();
	$searchbasic->recType = new RecordRef();
	$searchbasic->recType->type = "customRecord";
	$searchbasic->recType->internalId = "118"; //118 is internal ID of travel booking
	$searchbasic->isInactive = $is_not_inactive; //exclude inactive records
	$searchbasic->customFieldList = new SearchCustomFieldList();
	$searchbasic->customFieldList->customField = $customSearchFields;

	$search = new CustomRecordSearch();
	$search->basic = $searchbasic;

	$service = new NetSuiteService();
	$search_adv = new CustomRecordSearchAdvanced();
	$search_adv->savedSearchId = "99";
	$search_adv->criteria = $search;
	$request = new SearchRequest();
	$request->searchRecord = $search_adv;

	$searchResponse = $service->search($request);
	if (!$searchResponse->searchResult->status->isSuccess) {
		echo "SEARCH ERROR";
	} else {
		//echo "SEARCH SUCCESS, records found: " . $searchResponse->searchResult->totalRecords . "\n";
		return $searchResponse->searchResult;
	}
}
?>