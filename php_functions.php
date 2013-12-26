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
	$start_date = "27/12/2013";
	$end_date = "31/12/2013";
	$start_time = "09:00";
	$end_time = "18:00";
	$time_interval="15";
	$date_interval="1";
	$timezone = new DateTimeZone('Asia/Taipei');
	$timeinterval = new DateInterval('PT'.$time_interval.'M');
	$startdate = DateTime::createFromFormat('d/m/Y H:i',$start_date." ".$start_time);
	$enddate = DateTime::createFromFormat('d/m/Y H:i',$end_date." ".$end_time);
	$time = DateTime::createFromFormat('d/m/Y H:i',$start_date." ".$start_time);
	//echo $date->format('d M Y \(D\) H:i:s ')."\n\n\n";

	$currentdate= $startdate;
	echo $currentdate->format('d M Y \(D\) H:i:s ')."<br />";
	echo $enddate->format('d M Y \(D\) H:i:s ')."<br />";
	echo "<select>";
	while ($currentdate < $enddate){
		echo "yes";
		$nexttime = $currentdate;
		$nexttime->add(new DateInterval('P'.$date_interval.'D'));
		echo '<option value="'.$currentdate->format('d M Y \(D\)').'">';
		echo $currentdate->format('d M Y \(D\)')/*.'-'.$nexttime->format('d M Y \(D\)')*/;
		echo '</option>';
		$currentdate = $nexttime;
	}
	echo "</select>";

}
?>