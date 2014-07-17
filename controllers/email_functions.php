<?php
/* -- simple php version
	
	$to = "ayanokoujibou@hotmail.com";
	$subject = "Test mail";
	$message = "Hello! This is a simple email message.";
	$from = "david@fern.com.hk";
	$headers = "From:" . $from;
	$success = mail($to,$subject,$message,$headers);
	if ($success) {echo "Mail Sent to".$to;} else {echo " mail failed";}
*/
/* PHP Mailer version */
?>

<?php


require '../lib/mail/class.phpmailer.php';
//Setting for William Cheng & Sons
function setmail(&$mail){

	require '../config.php';
	$mail->IsSMTP();
	$mail->IsHTML(true);
	$mail->SMTPDebug  = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
	$mail->SetFrom($from_email_system, 'William Cheng & Son');
	$mail->AddReplyTo($replyto_email,'William Cheng & Son');
	$mail->Port       = 25;
	//SMTP setting at david's home
	/*
	$mail->SMTPAuth   = false;
	ini_set('SMTP','smtp.hkbn.net');
	$mail->Host       = 'smtp.hkbn.net';
	*/
	// SMTP setting elsewhere 
	/*
	$mail->Host       = 'mail.willsonbooking.fern.com.hk';
	$mail->SMTPAuth   = true;
	$mail->Username   = "booking@willsonbooking.fern.com.hk";
	$mail->Password   = "willsontrip1";
	*/
	$mail->Host       = 'smtp.williamcheng-son.com';
	$mail->SMTPAuth   = true;
	$mail->Username   = "appointment@williamcheng-son.com";
	$mail->Password   = "appointment9216";
}
function send_register_email($to,$fname,$lname,$pwd){
	require '../config.php';
	$mail = new PHPMailer();
	setmail($mail);
	$mail->AddAddress($to,$fname." ".$lname);
	$mail->Subject = 'Thank you for your registration on William Cheng & Son';
	$mail->Body = "<span style='font-family:vendara'>"
				."<p>Dear ".$fname." ".$lname.",</p>"
				."<p>Thank your for your registration on Wiliam Cheng & Son's customer portal</p>"
				."<p>We have assigned the following password for the first time access:</p>"
				."<table><tr><td>Password : </td><td style='font-style:italic'>".$pwd."</td></tr></table>"
				.'<p>We strongly recommend you to modify your password after first login to the portal. You can do so by choosing "Change Password" option in the customer portal.'
				."<p>Should you encounter any difficulty or abnormal situation during the process, please kindly inform us at <a href='customerservice@williamcheng-son.com'>customerservice@williamcheng-son.com</a>. Your participation is highly appreciated.</p>"
				."<p>Yours sincerely,</p>"
				."<p>Customer Services Department<br />WILLIAM CHENG & SON</p>"."</span>";
	
	//$file=fopen($email_logfile,"a");
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
	//fclose($file);
}

function send_reset_password_email($to,$fname,$lname,$pwd){
	require '../config.php';
	$mail = new PHPMailer();
	setmail($mail);
	$mail->AddAddress($to,$fname." ".$lname);
	$mail->Subject = 'Your new password for William Cheng & Son';
	$mail->Body = <<<codeblock
<span style='font-family:vendara'>
<p>Dear {$fname} {$lname},</p>
<p>As requested, your password has now been reset. Your new password is as follow:</p>
<table><tr><td>Password : </td><td style='font-style:italic'>{$pwd}</td></tr></table>
<p>We strongly recommend you to modify your password after first login to the portal. You can do so by choosing "Change Password" option in the customer portal.</p>
<p>Should you encounter any difficulty or abnormal situation during the process, please kindly inform us at <a href='customerservice@williamcheng-son.com'>customerservice@williamcheng-son.com</a>. Your participation is highly appreciated.</p>
<p>Yours sincerely,</p>
<p>Customer Services Department<br />WILLIAM CHENG & SON</p></span>
codeblock;
	//$file=fopen($email_logfile,"a");
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
}

function send_booking_cs_email($to,$eventName,$bookingID,$customerID,$action){

	$mail = new PHPMailer();
	setmail($mail);
	$mail->AddAddress($to,"CS Team");
	$mail->Subject = 'Event: '.$eventName.' - An booking has been '.$action;
	$mail->Body = "<span style='font-family:vendara'>";
	$mail->Body .="<p>Dear CS Team,</p>";
	$mail->Body .="<p>Please note that the following appointment has been ".$action.".</p>";
	$mail->Body .="<table>";
	$mail->Body .="<tr><td>Event Name : </td><td style='font-style:italic'>".$eventName."</td></tr>";
	$mail->Body .="<tr><td>Booking Number : </td><td style='font-style:italic'>".$bookingID."</td></tr>";
	$mail->Body .="<tr><td>Customer ID : </td><td style='font-style:italic'>".$customerID."</td></tr>";
	$mail->Body .="</table>";
	$mail->Body .="<p>Regds,</p>";
	$mail->Body .="<p>Booking System</p>"."</span>";
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!CS";
	}
}

function booking_customer_email($to,$eventName,$bookingID,$email_detail,$action){

	$mail = new PHPMailer();
	setmail($mail);
	$mail->AddAddress($to,$email_detail["firstname"]." ".$email_detail["lastname"]);
	$mail->Subject = 'Event: '.$eventName.' - Your booking has been '.$action;
	$mail->Body = "<span style='font-family:vendara'>";
	$mail->Body .="<p>Dear ".$email_detail["firstname"]." ".$email_detail["lastname"]."</p>";
	$mail->Body .="<p>Thank you very much for your appointment with William Cheng & Son. Here are your booking details:</p>";
	$mail->Body .="<table>";
	$mail->Body .="<tr><td style='width:140px'>Event Name : </td><td >".$eventName."</td></tr>";
	$mail->Body .="<tr><td style='width:140px'>Booking Number : </td><td>".$bookingID."</td></tr>";
	$mail->Body .="<tr><td style='width:140px'>Date: </td><td>".$email_detail["appoint_date"]."</td></tr>";
	$mail->Body .="<tr><td style='width:140px'>Time: </td><td>".$email_detail["appoint_time"]."</td></tr>";
	$mail->Body .="<tr><td style='width:140px'>Venue : </td><td>".$email_detail["venue"]."</td></tr>";
	$mail->Body .="</table>";
	$mail->Body .="<p>Should you have any problem, please do not hesitate to contact our Customer Services Department at <a href='mailto:appointment@williamcheng-son.com'>appointment@williamcheng-son.com</a>.</p>";
	$mail->Body .="<p>Yours sincerely,</p>";
	$mail->Body .="<p>Customer Services Department<br />WILLIAM CHENG & SON</p>"."</span>";
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!cust";
	}
}
function delete_booking_customer_email($to,$eventName,$bookingID,$email_detail,$action){

	$mail = new PHPMailer();
	setmail($mail);
	$mail->AddAddress($to,$email_detail["firstname"]." ".$email_detail["lastname"]);
	$mail->Subject = 'Event: '.$eventName.' - Your booking has been '.$action;
	$mail->Body = "<span style='font-family:vendara'>";
	$mail->Body .="<p>Dear ".$email_detail["firstname"]." ".$email_detail["lastname"].",</p>";
	$mail->Body .="<p>We are sorry to know that you have cancelled your appointment with booking number <b>".$bookingID.".</b></p>";
	$mail->Body .="<p>If you would like to book again, please login to our customer portal at <a href='www.williamcheng-son.com/customer_portal/'>www.williamcheng-son.com/customer_portal/</a></p>";
	$mail->Body .="<p>Should you have any problem, please do not hesitate to contact our Customer Services Department at <a href='mailto:appointment@williamcheng-son.com'>appointment@williamcheng-son.com</a>.</p>";
	$mail->Body .="<p>Yours sincerely,</p>";
	$mail->Body .="<p>Customer Services Department<br />WILLIAM CHENG & SON</p>"."</span>";
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
}

function del_email(&$mail,$to,$booking,$date,$time,$venue,$link,$fname,$lname){
	setgmail($mail);
	$mail->AddAddress($to, $fname." ".$lname); //need to change	
	$mail->Subject = 'Booking cancellation with William Cheng & Son	';
	$mail->Body = "<span style='font-family:vendara'>"
		."<p>Dear ".$fname." ".$lname.",</p>"
		."<p>We are sorry to know that you have cancelled your appointment with booking number ".$booking."</p>"
		."<p>If you would like to book again, please go to the following link. <br /><a href='http://appointment.williamcheng-son.com'>http://appointment.williamcheng-son.com</a></p>"
		."<p>Should you have any enquiry, please do not hesitate to contact our Customer Services Department at <a href='mailto:appointment@williamcheng-son.com'>appointment@williamcheng-son.com</a>.</p>"
		."<p>Yours sincerely,</p>"
		."<p>Customer Services Department<br />WILLIAM CHENG & SON</p>"."</span>";
}
function add_email(&$mail,$to,$booking,$date,$time,$venue,$link,$fname,$lname){
	setgmail($mail);
	$mail->AddAddress($to, $fname." ".$lname); //need to change	
	$mail->Subject = 'Confirmation email for your successful booking/amendment with William Cheng & Son';
	$mail->Body = "<span style='font-family:vendara'>"
		."<p>Dear ".$fname." ".$lname."</p>"
		."<p>Thank you very much for your appointment with William Cheng & Son. Here are your booking details:</p>"
		."<p>Booking number: ".$booking."<br />Date: ".$date."<br />Time: ".$time."<br />Venue: ".$venue."</p>"
		."<p>If you wish to amend/cancel your booking, please click on the url below:</p>"
		."<p><a href='".$link."'>".$link."</a></p>"
		."<p>Should you have any problem, please do not hesitate to contact our Customer Services Department at <a href='mailto:appointment@williamcheng-son.com'>appointment@williamcheng-son.com</a>.</p>"
		."<p>Yours sincerely,</p>"
		."<p>Customer Services Department<br />WILLIAM CHENG & SON</p>"."</span>";
}
function add_cs(&$mail,$type,$to,$booking,$fname,$lname,$c_email,$phone,$address,$date,$time,$link,$link2,$purpose,$purchase_item,$alter_item,$other,$measure,$no_com,$name_com,$refer,$message){
	setgmail($mail);
	$temp = "";
	if ($type!='cancelled'){ // add the link if this is not an email triggered by cancellation
		$temp = "<p>You can amend/delete the booking at this link :<br /><a href='".$link2."'>".$link2."</a></p>";
	}
	$mail->AddAddress($to, "CS team"); //need to change	
	$mail->Subject = 'Booking '.$type.' with William Cheng & Son';
	$mail->Body = "<span style='font-family:vendara'>"
	."<p>Dear CS Team,</p>"
	."<p>Please note that the following appointment:</p>"
	."Booking number: ".$booking."<br />Customer first name: ".$fname."<br />Customer last name: ".$lname."<br />"
	."Customer email address: ".$c_email."<br />Customer contact phone number:".$phone."<br />Customer address: ".$address."<br />"
	."Appointment date/timing: ".$date."/".$time."<br />Purpose of visit: ".$purpose."<br />"
	."Item wish to be purchased: ".$purchase_item."<br />Item wish to be altered: ".$alter_item."<br />Other details: ".$other."<br />"
	."Change of measurement: ".$measure."<br />No of companions: ".$no_com."<br />Name of companions: ".$name_com."<br />"
	."Referred by: ".$refer."<br />Message to us: ".$message."</p>"
	."<p>was ".$type.".</p>"
	."<p>You can access the booking report via the url below</br><br />"
	."<a href='".$link."'>".$link."</a><p />"
	.$temp	
	."Regds<br />Booking System</span>"	;
}


?>
