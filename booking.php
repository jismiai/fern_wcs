<?php
require_once 'controllers/log_control.php';
require_once 'netsuite_functions.php';
require_once 'php_functions.php';

//Make sure this page is loading a correct id.
if (!isset($_GET['id'])){
	header('Location:'.$_SERVER["REQUEST_URI"]."?id=3");
}
$event_id = $_GET['id'];
$event = getCustRec("80",$event_id);
foreach ($event->customFieldList->customField as $customfield){
	if ($customfield->scriptId == "custrecord_event_name"){
		$event_name = $customfield->value;
		break;
	}
}

//perform booking searching to determine add/edit mode
$booking_records = searchBookingByEventAndCustomer($event_id,$_SESSION['customerID']);

if($booking_records->totalRecords == 0){
	$booking_exist = false;
} else {
	$booking_exist = true;
}
//if in edit mode, parse record parameters, perform variable assignment
$current_booking = array();
if ($booking_exist){
	foreach ($booking_records->recordList->record[0]->customFieldList->customField as $value){
		$current_booking[$value->scriptId] = $value->value;
	}
}
/*Obtain dynamic information such as date/time selection lists*/
$time_config = timeFunctions();
//other selection lists
$item_option = array( // multiselection list
		"Shirt","Suit","Jacket","Pants","Coat","Vest","Dress","Skirt","Blouse","Other"
);
$ch_measure_option = array( // selection list
		"Weight increase","Weight loss","No change"
);

if ($booking_exist){	
	$current_date = array(
			date => dateNetsuitetoPHP($current_booking["custrecord_booking_date"]),
			time => $current_booking["custrecord_booking_time"]
	);
}
//javascript to append;
$custom_head ='
<script>
$(document).ready(function(){
	if($("#purpose_purchase").attr("checked") != "checked"){
		$("#purchase_selection").hide();
	}
	if($("#purpose_alter").attr("checked") != "checked"){
		$("#alter_selection").hide();
	}
	$("#purpose_purchase").click(function(){
		if (this.checked){
			$("#purchase_selection").show();
		} else {
			$("#purchase_selection").hide();
		}
	});
	
	$("#purpose_alter").click(function(){
		if (this.checked){
			$("#alter_selection").show();
		} else {
			$("#alter_selection").hide();
		}
	});
	$("#booking_cancel").click(function(){
		var r=confirm("Booking cancellation confirm?");
		if (r==true){
			$("#bookingform").attr("action","controllers/booking_handler.php?delete=yes");
			$("#bookingform").submit();
		}
	});
});
</script>
';
$custom_title="Your Booking";
include("templates/head_tag.php");
?>
<script>
$(document).ready(function(){
  var event_id = <?php echo $event_id; ?>;
  var old_time = $('#appoint_time').val();
  var old_date = $('#appoint_date').val();
  var today = $('#appoint_date').val();
    $.get("get_timeslot.php",{eventid:event_id,date:old_date,time:old_time,today:today},function(data){
		$("#appoint_time_wrapper").html(data);
		//alert("Data: " + data + "\nStatus: " + status);
    },"html");
  $("#appoint_date").change(function(){
	var today = $('#appoint_date').val();
    $.get("get_timeslot.php",{eventid:event_id,date:old_date,time:old_time,today:today},function(data){
		$("#appoint_time_wrapper").html(data);
		//alert("Data: " + data + "\nStatus: " + status);
    },"html");
  });
});
</script>
<div class="col-sm-offset-2">
<h2 >Your Booking</h2>
</div>
<form class="form-horizontal" role="form" id="bookingform" action="controllers/booking_handler.php" method="post">
	<fieldset>
		<div class="form-group">
			<label for="" class="col-sm-3 col-sm-offset-1 control-label">Event ID</label>
			<div class="col-sm-5">
				<input type="text" name="event_name" id="event_name" class="form-control" value="<?php echo $event_name; ?>" readonly="readonly" />
				<input type="hidden" name="event_id" id="event_id" class="form-control" value="<?php echo $event_id; ?>" readonly="readonly" />
			</div>
		</div>
		<div class="form-group">
			<label for="appoint_date" class="col-sm-3 col-sm-offset-1 control-label">Appointment timing</label>
			<div class="col-sm-3">
				<?php printSelectDateList($time_config,"form-control","appoint_date",$current_date); ?>
			</div>
			<div class="col-sm-2" id="appoint_time_wrapper">
				<?php printSelectTimeList($time_config,$current_date["date"],"form-control","appoint_time",$current_date,$vacancy_array); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="" class="col-sm-3 col-sm-offset-1 control-label">Purpose of Visit</label>
			<div class="col-sm-5">
				<label class="checkbox-inline">
					<input type="checkbox" name="purpose_purchase" id="purpose_purchase" value="Purchase" <?php checkboxFromString($current_booking['custrecord_booking_purpose'],"Purchase"); ?> />
					Purchase
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" name="purpose_alter" id="purpose_alter"value="Alteration" <?php checkboxFromString($current_booking['custrecord_booking_purpose'],"Alteration"); ?> />
					Alteration
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" name="purpose_other" id="purpose_other" value="Others" <?php checkboxFromString($current_booking['custrecord_booking_purpose'],"Others"); ?> />
					Others
				</label>
			</div>
		</div>
		<div class="form-group" id="purchase_selection">
			<label for="purchase_item" class="col-sm-3 col-sm-offset-1 control-label">
				Items wish to be purchased<br />
			</label>
			<div class="col-sm-5">
				<?php multiSelectListFromArray($item_option,"form-control","purchase_item",$current_booking["custrecord_booking_purchase_item"]); ?>	
				<span class="help-block">(Hold Ctrl to select multiple)</span>
			</div>
		</div>
		<div class="form-group" id="alter_selection">
			<label for="alter_item" class="col-sm-3 col-sm-offset-1 control-label">Items wish to be altered</label>
			<div class="col-sm-5">			
				<?php multiSelectListFromArray($item_option,"form-control","alter_item",$current_booking["custrecord_booking_alter_item"]); ?>				
				<span class="help-block">(Hold Ctrl to select multiple)</span>
			</div>
		</div>
		<div class="form-group">
			<label for="otherdetails" class="col-sm-3 col-sm-offset-1 control-label">Other Details</label>
			<div class="col-sm-5">
				<input type="text" name="otherdetails" id="otherdetails" class="form-control" value="<?php echo $current_booking["custrecord_booking_otherdetails"]; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="ch_measure" class="col-sm-3 col-sm-offset-1 control-label">Change of measurement</label>
			<div class="col-sm-5">				
				<?php selectListFromArray($ch_measure_option,"form-control","ch_measure",$current_booking["custrecord_booking_ch_measure"]); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="num_companion" class="col-sm-3 col-sm-offset-1 control-label">No. of companions</label>
			<div class="col-sm-5">
				<input type="text" name="num_companion" id="num_companion" class="form-control" value="<?php echo $current_booking["custrecord_booking_num_companion"]; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="name_companion" class="col-sm-3 col-sm-offset-1 control-label">Name of companions</label>
			<div class="col-sm-5">
				<input type="text" name="name_companion" id="name_companion" class="form-control" value="<?php echo $current_booking["custrecord_booking_name_companion"]; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="refer" class="col-sm-3 col-sm-offset-1 control-label">Referred By</label>
			<div class="col-sm-5">
				<input type="text" name="refer" id="refer" class="form-control" value="<?php echo $current_booking["custrecord_booking_refer"]; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="message" class="col-sm-3 col-sm-offset-1 control-label">Message to us</label>
			<div class="col-sm-5">
				<input type="text" name="message" id="message" class="form-control" value="<?php echo $current_booking["custrecord_booking_message"]; ?>" />
			</div>
		</div>
		<div class="form-group" style="padding:20px 0;">
			<div class="col-sm-offset-4 col-sm-4" >
				<button type="submit" value="Update" class="btn btn-wcs-default">Submit</button>
				<?php 
				if ($booking_exist)
				echo '<button type="button" class="btn btn-wcs-default" id="booking_cancel">Cancel booking</button>';
				?>
				<a href="portal.php" class="btn btn-link">Back</a>
			</div>
		</div>
	</fieldset>
</form>

<script type="text/javascript" src="lib/jquery.validate.js"></script>
<script type="text/javascript" src="lib/wcs_validation.js"></script>

<?php 
require_once 'templates/footer_tag.php';
?>