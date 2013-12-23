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
?>