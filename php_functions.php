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
	$event_start_date = "13/2/2014";
	$event_end_date = "20/2/2014";
	$event_start_time = "09:00";
	$event_end_time = "18:00";
	$event_time_interval="15";
	$event_default_address = array(
		"short" => "Sydney",
		"long" => "Sussex Meeting Room<br />at ADINA APARTMENT HOTEL.511 Kent Street, Sydney, NSW 2000.<br />Phone: (02) 9274-0000"
	);
	$timeinterval = new DateInterval('PT'.$event_time_interval.'M');
	$excluded_date_str = "16/2/2014";
	$specific_time_str = "18/2/2014,09:00,15:00";
	$address_str = "1;Brisbane;RENDEZVOUS HOTEL BRISBANE.<br />225 Ann Street. (CNR Edward Street)<br />Brisbane, QLD 4000. Phone: (07) 3001-9888<br />(at Room 3032)";
	$address_str .= "|2;Melbourne;ORCHID MEETING ROOM<br />at ADINA APARTMENT HOTEL<br />189 Queen Street, Melbourne, VIC 3000<br />Phone: (03) 9934-0000";
	$address_by_date = "17/2/2014,1;18/2/2014,1;19/2/2014,2;20/2/2014,2";
	
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
	$time_config['timeinterval'] = $timeinterval;
	$time_config['excluded_date_str'] = $excluded_date_str;
	$time_config['specific_time_str'] = $specific_time_str;
	$time_config['address_str'] = $address_str;
	$time_config['address_by_date'] = $address_by_date;
	//$time_config[''] = $;
	
	//load vacancy list

	return $time_config;	
}

function printSelectDateList($time_config,$classes,$id){	 //Selection list of date
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
	$currentdate= clone($startdate);
	//echo $currentdate->format('d M Y \(D\) H:i:s ')."<br />";
	//echo $enddate->format('d M Y \(D\) H:i:s ')."<br />";
	echo '<select name="'.$id.'" id="'.$id.'" class="'.$classes.'">';
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
			echo '<option value="'.$currentdate->format('j/n/Y').'">';
			//insert overrided short-form address if the $address_by_date is set
			if (array_key_exists($currentdate->format('j/n/Y'),$address_by_date)){ 
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
function printSelectTimeList($time_config,$today,$classes,$id){	//Selection list of time
	
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
	$currenttime = clone($starttime);
	//echo "<br />".$currenttime->format('d M Y \(D\) H:i:s ')."<br />";
	//echo $endtime->format('d M Y \(D\) H:i:s ')."<br />";
	echo '<select name="'.$id.'" id="'.$id.'" class="'.$classes.'">';
	while ($currenttime < $endtime){
		echo '<option value="'.$currenttime->format('G:i').'">';
		echo $currenttime->format('G:i').'-';
		$currenttime->add(new DateInterval('PT'.$time_config['event_time_interval'].'M')); // compute next interval now
		echo $currenttime->format('G:i');
		echo '</option>';
	}
	echo "</select>";
}
?>