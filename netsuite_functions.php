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

function searchBookingByEventAndCustomer($customerID){
	$service = new NetSuiteService();
	//$service->setSearchPreferences(false, 20);
	$listRef = new ListOrRecordRef();
	$listRef->internalId = $customerID;
	$listRef->typeId = "-2";

	$listSearchField = new SearchMultiSelectCustomField();
	$listSearchField->operator = "anyOf";
	$listSearchField->scriptId = 'custrecord_booking_custid';
	$listSearchField->searchValue = $listRef;

	echo "<pre>";
	print_r($listSearchField);
	echo "</pre>";

	$search = new CustomRecordSearchBasic();
	$search->recType = new RecordRef();
	$search->recType->type = "customRecord";
	$search->recType->internalId = "118";
	$search->customFieldList = new SearchCustomFieldList();
	$search->customFieldList->customField = array($listSearchField);
	echo "<pre>";
	print_r($search);
	echo "</pre>";
	$request = new SearchRequest();
	$request->searchRecord = $search;

	$searchResponse = $service->search($request);

	echo "<pre>";
	print_r($searchResponse->searchResult->recordList);
	echo "</pre>";

	if (!$searchResponse->searchResult->status->isSuccess) {
		return -1;
	} else {
		return $searchResponse->searchResult->totalRecords;
	}
}
//searchBookingByEventAndCustomer("556");
?>