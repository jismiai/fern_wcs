<?php
function randomPassword() {
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!?";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function timeFunctions(){
	//default configuration
	$date_interval="1";
	$timezone = new DateTimeZone('Asia/Taipei');
	
	//configuration from record
	$event_start_date = "3/6/2015";
	$event_end_date = "6/6/2015";
	$event_start_time = "09:00";
	$event_end_time = "18:00";
	$event_time_interval="15";
	$event_slots=4;
	$event_default_address = array(
		"short" => "London",
		"long" => "PICASSOO ROOM<br />MERCURE London Bridge Hotel<br />71-79 Southwark Street, London, SE1 0JA, UK<br />Phone number: (+44) 0207 6600683"
	);
	$timeinterval = new DateInterval('PT'.$event_time_interval.'M');
	$excluded_date_str = "";
	$specific_time_str = "";
	$address_str = "";
	$address_str .= "";
	$address_by_date = "";
	/* Achieve format of Australia 2014 */
	/*
	$event_start_date = "13/2/2014";
	$event_end_date = "20/2/2014";
	$event_start_time = "09:00";
	$event_end_time = "18:00";
	$event_time_interval="15";
	$event_slots=5;
	$event_default_address = array(
		"short" => "Sydney",
		"long" => "Sussex Meeting Room<br />at ADINA APARTMENT HOTEL.511 Kent Street, Sydney, NSW 2000.<br />Phone: (02) 9274-0000"
	);
	$timeinterval = new DateInterval('PT'.$event_time_interval.'M');
	$excluded_date_str = "16/2/2014";
	$specific_time_str = "18/2/2014,09:00,15:00";
	$address_str = "1;Brisbane;Room 3032 at RENDEZVOUS HOTEL BRISBANE.<br />225 Ann Street. (CNR Edward Street)<br />Brisbane, QLD 4000. Phone: (07) 3001-9888";
	$address_str .= "|2;Melbourne;ORCHID MEETING ROOM<br />at ADINA APARTMENT HOTEL<br />189 Queen Street, Melbourne, VIC 3000<br />Phone: (03) 9934-0000";
	$address_by_date = "17/2/2014,1;18/2/2014,1;19/2/2014,2;20/2/2014,2";
	** end of achieve **/
	//package configurations into an array
	$time_config = array();
	$time_config['date_interval'] = $date_interval;
	$time_config['timezone'] = $timezone;
	$time_config['event_start_date'] = $event_start_date;
	$time_config['event_end_date'] = $event_end_date;
	$time_config['event_start_time'] = $event_start_time;
	$time_config['event_end_time'] = $event_end_time;
	$time_config['event_default_address'] = $event_default_address;
	$time_config['event_time_interval'] = $event_time_interval;
	$time_config['event_slots'] = $event_slots;
	$time_config['timeinterval'] = $timeinterval;
	$time_config['excluded_date_str'] = $excluded_date_str;
	$time_config['specific_time_str'] = $specific_time_str;
	$time_config['address_str'] = $address_str;
	$time_config['address_by_date'] = $address_by_date;
	//$time_config[''] = $;
	
	//load vacancy list

	return $time_config;	
}

function printSelectDateList($time_config,$classes,$id,$current){	 //Selection list of date
	/* -- function explained
	 * This function reads parameters and outputs a suitable <select> list in HTML
	 * $time_config : an array which stores the event information such as start time, time interval of this event
	 * $classes : class attritbute of output list
	 * $id : id attribute of output list
	 * $current : an array storing this customer's current booking;
	*/
	
	/*
	echo "<pre>";
	var_dump($time_config);
	echo "</pre>";
	*/
	//run-time configuration, parse excluded dates
	$excluded_date = explode(",", $time_config['excluded_date_str']);

	//run-time configuration, parse alternative addresses
	$addresses_array = explode("|", $time_config['address_str']);
	$addresses = array();
	foreach ($addresses_array as $addresses_info){
		$temp = explode(";",$addresses_info);
		$addresses[$temp[0]] = $temp;
	}
	/*
	echo "<pre>";
	print_r($addresses);
	echo "</pre>";
	*/
	//run-time configuration, parse addresses by date (to output short form address
	$address_by_date_array = explode(";", $time_config['address_by_date']);
	$address_by_date = array();
	foreach ($address_by_date_array as $address_info){
		$temp = explode(",",$address_info);
		$address_by_date[$temp[0]] = $temp[1];
	}
	/*
	echo "<pre>";
	print_r($address_by_date);
	echo "</pre>";
	*/
	//
	$startdate = DateTime::createFromFormat('d/m/Y H:i',$time_config['event_start_date']." 09:00");
	$enddate = DateTime::createFromFormat('d/m/Y H:i',$time_config['event_end_date']." 18:00");
	$currentdate = $startdate;
	//$currentdate= clone($startdate);
	//echo $currentdate->format('d M Y \(D\) H:i:s ')."<br />";
	//echo $enddate->format('d M Y \(D\) H:i:s ')."<br />";
	echo '<select name="'.$id.'" id="'.$id.'" class="'.$classes.'">';
	echo '<option value="">Select</option>';
	while ($currentdate < $enddate){
		$date_excluded = false;	
		foreach ($excluded_date as $date_value){
				$data .=$date_value.",".$currentdate->format('j/n/Y')."<br />";
				if (strcmp($date_value,$currentdate->format('j/n/Y')) ==0){
					$date_excluded=true;
					break;
				}
		}
		if (!$date_excluded){
			if (strcmp($current['date'],$currentdate->format('j/n/Y')) == 0){
				echo '<option value="'.$currentdate->format('j/n/Y').'" selected="selected">';
			} else {
			echo '<option value="'.$currentdate->format('j/n/Y').'">';
			}
			//insert overrided short-form address if the $address_by_date is set
			if (array_key_exists($currentdate->format('j/n/Y'),$address_by_date)){ 
				//if special address is assigned, use date-specific address loaded from record
				echo $addresses[$address_by_date[$currentdate->format('j/n/Y')]][1]." | ";
			} else { //insert default short-form address
				echo $time_config['event_default_address']['short']." | ";
			}
			echo $currentdate->format('d M Y \(D\)');
			echo '</option>';
		}
		$currentdate->add(new DateInterval('P'.$time_config['date_interval'].'D'));
	}
	echo "</select>";
}	
function printSelectTimeList($time_config,$today,$classes,$id,$current,$vacancy_array){	//Selection list of time
	/* -- function explained
	 * This function reads parameters and outputs a suitable <select> list of time-slotsin HTML
	* $time_config : an array which stores the event information such as start time, time interval
	* $today : the date which the time-slots are requested
	* $classes : class attritbute of output list
	* $id : id attribute of output list
	* $current : an array storing this customer's current booking;
	* $vacancy_array : an array whose key is time_slot, value is no of bookings of the slot
	*/
	
	//run-time configuration, parse excluded dates
	$specific_time_array = explode(";", $time_config['specific_time_str']);
	$specific_time = array();
	foreach ($specific_time_array as $specific_time_info){
		$temp = explode(",",$specific_time_info);
		$specific_time[$temp[0]] = $temp;
	}
	/*
	echo "<pre>";
	var_dump($specific_time);
	echo "</pre>";
	*/
	if (array_key_exists($today,$specific_time)){
		$today_start_time = $specific_time[$today][1];
		$today_end_time = $specific_time[$today][2];
	} else {
		$today_start_time = $time_config['event_start_time'];
		$today_end_time = $time_config['event_end_time'];
	}
	$starttime = DateTime::createFromFormat('d/m/Y H:i',$today." ".$today_start_time);
	$endtime = DateTime::createFromFormat('d/m/Y H:i',$today." ".$today_end_time);
	$currenttime = $starttime;
	//$currenttime = clone($starttime);
	//echo "<br />".$currenttime->format('d M Y \(D\) H:i:s ')."<br />";
	//echo $endtime->format('d M Y \(D\) H:i:s ')."<br />";
	echo '<select name="'.$id.'" id="'.$id.'" class="'.$classes.'">';
	echo '<option value="">Select</option>';
	while ($currenttime < $endtime){
		if (strcmp($current['time'],$currenttime->format('H:i')) == 0 && strcmp($current['date'],$today) == 0){ //is the time specific by existing record
			echo '<option value="'.$currenttime->format('H:i').'" selected="selected">';
		} else { //time slot not specified by existing record
			if ($vacancy_array[$currenttime->format('H:i')] < $time_config["event_slots"]){
				echo '<option value="'.$currenttime->format('H:i').'">';
			} else {
				echo '<option value="'.$currenttime->format('H:i').'" disabled>(Full) ';
			}
		}
		echo $currenttime->format('H:i').'-';
		$currenttime->add(new DateInterval('PT'.$time_config['event_time_interval'].'M')); // compute next interval now
		echo $currenttime->format('H:i');
		echo '</option>';
	}
	echo "</select>";
}

function explodeToArray($str,$delimit1,$delimit2,$index){
	/*explode a string into an 2d array, returns to the 2d array
	 * $str = the string
	 * $delimit1 = higher level delimiter (longer string)
	 * $delimit2 = the 2nd tier delimiter
	 * $index = see the code yourself to understand
	 */
	$temp_1d_array = explode($delimit1,$str);
	$temp_2d_array = array();
	foreach($temp_1d_array as $value){
		$temp = explode($delimit2,$value);
		$temp_2d_array[$temp[0]] = $temp[$index];
	}
	return $temp_2d_array;
}
function datePHPtoNetsuite($in_date){
	/* input dd/mm/yyyy format and output yyyy-dd-mmThh:mm:sss.xxx+hh:mm */
	$temp_date = DateTime::createFromFormat('d/m/Y',$in_date);
	return $temp_date->format('Y-m-d')."T19:00:00.000+08:00";
}

function dateNetsuitetoPHP($in_date){
	/* input yyyy-dd-mmThh:mm:sss.xxx+hh:mm format and output dd/mm/yyyy */
	$date_array = explode("T", $in_date);
	$temp_date = DateTime::createFromFormat('Y-m-d',$date_array[0]);
	$temp_date->add(new DateInterval('P1D')); // manually add 1 day due to Netsuite timezone setting
	return $temp_date->format('j/n/Y');
}
function selectListFromArray($array,$classes,$id,$selected){
	/* -- function explained
	 * This function reads array and outputs a suitable <select> list in HTML
	* $array : an array which stores available options
	* $classes : class attritbute of output list
	* $id : id attribute of output list
	* $selected : an string containing the options you want to selected
	*/
	echo '<select class="'.$classes.'" id="'.$id.'" name="'.$id.'">';
	echo '<option value="">Select</option>';
	foreach ($array as $value){
		if (!(strpos($value,$selected) === false)){
			echo '<option value="'.$value.'" selected="selected">'.$value.'</option>';
		} else {
			echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}
	echo '</select>';
}
function multiSelectListFromArray($array,$classes,$id,$selected){
	/* -- function explained
	 * This function reads array and outputs a suitable <select> list in HTML
	* $array : an array which stores available options
	* $classes : class attritbute of output list
	* $id : id attribute of output list
	* $selected : an string containing the options you want to selected
	*/
	echo '<select multiple="multiple" class="'.$classes.'" id="'.$id.'" name="'.$id.'[]">';
	foreach ($array as $value){
		if (!(strpos($selected,$value) === false)){
			echo '<option value="'.$value.'" selected="selected">'.$value.'</option>';
		} else {
			echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}
	echo '</select>';
}
function checkboxFromString($str, $keyword){
	/* this function has an output if $str contains $keyword */
	if (!(strpos($str,$keyword) === false)){
		echo 'checked="checked"';
	}
}
?>