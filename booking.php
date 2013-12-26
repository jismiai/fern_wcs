<?php
require_once 'controllers/log_control.php';

//perform booking searching to determine add/edit mode


//if in edit mode, parse record parameters, perform variable assignment


//javascript to append;
$custom_head ='
<script>
$(document).ready(function(){
	if($("#purpose_purchase").attr("checked") != "checked"){
		$("#purchase_selection").hide();
	}
	if(!$("#purpose_alter").attr("checked") != "checked"){
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
});
</script>
';
$custom_title="Your Booking";
include("templates/head_tag.php");
?>
<div class="col-sm-offset-2">
<h2 >Your Booking</h2>
</div>
<form class="form-horizontal" role="form" id="wcsform" action="controllers/booking_handler.php" method="post">
	<fieldset>
		<div class="form-group">
			<label for="" class="col-sm-4 col-sm-offset-1 control-label">Event</label>
			<div class="col-sm-4">
				<input type="text" name="" id="" class="form-control" value="Australia 2014" readonly="readonly" />
				<input type="text" name="" id="" class="form-control" value="Australia 2014" readonly="readonly" />
			</div>
		</div>
		<div class="form-group">
			<label for="appoint_date" class="col-sm-4 col-sm-offset-1 control-label">Appointment timing</label>
			<div class="col-sm-2">
				<select name="appoint_date" id="appoint_date" class="form-control">
					<option>Feb 14</option>
					<option>Feb 15</option>
				</select>
			</div>
			<div class="col-sm-2">
				<select name="appoint_time" id="appoint_time" class="form-control">
					<option>14:00</option>
					<option>15:00</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="" class="col-sm-4 col-sm-offset-1 control-label">Purpose of Visit</label>
			<div class="col-sm-4">
				<label class="checkbox-inline">
					<input type="checkbox" name="purpose_purchase" id="purpose_purchase" value="Purchase" />
					Purchase
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" name="purpose_alter" id="purpose_alter"value="Alteration" />
					Alteration
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" name="purpose_other" id="purpose_other" value="Others" />
					Others
				</label>
			</div>
		</div>
		<div class="form-group" id="purchase_selection">
			<label for="purchase_item" class="col-sm-4 col-sm-offset-1 control-label">
				Items wish to be purchased<br />
			</label>
			<div class="col-sm-4">
				<select multiple="multiple" name="purchase_item[]" id="purchase_item" class="form-control">
					<option value="Shirt">Shirt</option>
					<option value="Suit">Suit</option>
					<option value="Pants">Pants</option>
					<option value="Coat">Coat</option>
					<option value="Vest">Vest</option>
					<option value="Dress">Dress</option>
					<option value="Skirt">Skirt</option>
					<option value="Blouse">Blouse</option>
					<option value="Other">Others</option>
				</select>
				<span class="help-block">(Hold Ctrl to select multiple)</span>
			</div>
		</div>
		<div class="form-group" id="alter_selection">
			<label for="alter_item" class="col-sm-4 col-sm-offset-1 control-label">Items wish to be purchased</label>
			<div class="col-sm-4">
				<select multiple="multiple" name="alter_item[]" id="alter_item" class="form-control">
					<option value="Shirt">Shirt</option>
					<option value="Suit">Suit</option>
					<option value="Pants">Pants</option>
					<option value="Coat">Coat</option>
					<option value="Vest">Vest</option>
					<option value="Dress">Dress</option>
					<option value="Skirt">Skirt</option>
					<option value="Blouse">Blouse</option>
					<option value="Other">Others</option>
				</select>
				<span class="help-block">(Hold Ctrl to select multiple)</span>
			</div>
		</div>
		<div class="form-group">
			<label for="otherdetails" class="col-sm-4 col-sm-offset-1 control-label">Other Details</label>
			<div class="col-sm-4">
				<input type="text" name="otherdetails" id="otherdetails" class="form-control" value="" />
			</div>
		</div>
		<div class="form-group">
			<label for="ch_measure" class="col-sm-4 col-sm-offset-1 control-label">Change of measurement</label>
			<div class="col-sm-4">
				<select class="form-control" name="ch_measure" id="ch_measure" value="" >
					<option value="" selected="selected">Select</option>
					<option value="Weight increase">Weight increase</option>
					<option value="Weight loss">Weight loss</option>
					<option value="No change">No change</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="num_companion" class="col-sm-4 col-sm-offset-1 control-label">No. of companions</label>
			<div class="col-sm-4">
				<input type="text" name="num_companion" id="num_companion" class="form-control" value="" />
			</div>
		</div>
		<div class="form-group">
			<label for="name_companion" class="col-sm-4 col-sm-offset-1 control-label">Name of companions</label>
			<div class="col-sm-4">
				<input type="text" name="name_companion" id="name_companion" class="form-control" value="" />
			</div>
		</div>
		<div class="form-group">
			<label for="refer" class="col-sm-4 col-sm-offset-1 control-label">Referred By</label>
			<div class="col-sm-4">
				<input type="text" name="refer" id="refer" class="form-control" value="" />
			</div>
		</div>
		<div class="form-group">
			<label for="message" class="col-sm-4 col-sm-offset-1 control-label">Message to us</label>
			<div class="col-sm-4">
				<input type="text" name="message" id="message" class="form-control" value="" />
			</div>
		</div>
		<div class="form-group" style="padding:20px 0;">
			<div class="col-sm-offset-5 col-sm-4" >
				<button type="submit" value="Update" class="btn btn-wcs-default" />Submit</button>
				<a href="portal.php" class="btn btn-link">Back</a>
			</div>
		</div>
	</fieldset>
</form>
<?php 
require_once 'templates/footer_tag.php';
?>