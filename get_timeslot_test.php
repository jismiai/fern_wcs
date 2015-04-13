<?php

require_once 'netsuite_functions.php';
require_once 'php_functions.php';

/*
$today = "15/2/2014";
$current_date = array(
		date => "15/2/2014",
		time => "10:15"
);*/
$event_id = $_GET["eventid"];
$today = $_GET["today"];
$current_date = array(
		date => $_GET["date"],
		time => $_GET["time"]
);
$vacancy_result = savedSearchVacancy($event_id,$today);
//echo "<pre>";
//print_r($vacancy_result);
//echo "</pre>";
$records = $vacancy_result->searchRowList->searchRow;
/* add the records found into an array */
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
echo "<pre>";
print_r($vacancy_array);
print_r($records);
echo "</pre>";
$time_config = timeFunctions();


printSelectTimeList($time_config,$today,"form-control","appoint_time",$current_date,$vacancy_array); 

?>
