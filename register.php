
<?php
$custom_head ='
<script>
$(document).ready(function(){
	if($("#sameshipping").attr("checked") == "checked"){
		$("#shipping_addr").hide();
	}
	$("#sameshipping").click(function(){
		if (this.checked){
			$("#shipping_addr").hide();
		} else {
			$("#shipping_addr").show();
		}
	});
});
</script>
';
include("templates/head_tag.php"); 
?>
<div class="">
	<h2>Customer Registration</h2>
	<p>Fill in the information below to register</p>
</div>
<form class="form-horizontal" role="form" id="wcsform" action="controllers/new_customer.php" method="post">
	<fieldset>
		<legend>Login Detail</legend>
		<div class="form-group">
			<label for="user_email" class="col-sm-4 control-label">Email:</label>
			<div class="col-sm-8">
				<input type="email" name="user_email" class="form-control" id="user_email" />
			</div>
		</div>
		<div class="form-group">
			<label for="user_password" class="col-sm-4 control-label">Password:</label>
			<div class="col-sm-8">
				<input type="password" name="user_password" class="form-control" id="user_password" />
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Personal Information</legend>
		<div class="form-group">
			<label for="salutation" class="col-sm-4 control-label">Salutation: </label>
			<div class="col-sm-8">
				<label class="radio-inline">
					<input type="radio" name="salutation" id="salutation_mr" class="radio_box" value="Mr." checked="checked">Mr.
				</label>
				<label class="radio-inline">
					<input type="radio" name="salutation" id="salutation_mr" class="radio_box" value="Ms." >Ms.
				</label>
				<label class="radio-inline">
					<input type="radio" name="salutation" id="salutation_mr" class="radio_box" value="Mrs." >Mrs.
				</label>
			</div>
		</div>
		<div class="form-group">
			<label for="firstname" class="col-sm-4 control-label">First & Middle Name:</label>
			<div class="col-sm-8">
				<input type="text" name="firstname" id="firstname" class="form-control" value="<?php //echo $customer->firstName; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="lastname" class="col-sm-4 control-label">Last Name:</label>
			<div class="col-sm-8">
				<input type="text" name="lastname" id="lastname" class="form-control" value="<?php //echo $customer->lastName; ?>" />
			</div>
		</div>
		<div class="form-group">
		<label for="companyname" class="col-sm-4 control-label">Company Name:</label>
			<div class="col-sm-8">
				<input type="text" name="companyname" id="companyname" class="form-control" value="<?php //echo $customer->companyName; ?>" />
			</div>	
		</div>
		<div class="form-group">
		<label for="phone" class="col-sm-4 control-label">Telephone Number</label>
			<div class="col-sm-8">
				<input type="text" name="phone" id="phone" class="form-control" value="<?php //echo $customer->phone; ?>" />
				<span style="color:#aaaaaa;font-size:80%">Suggested format : +YYY-ZZ-XXXXXXX, where "YYY" is the country code, "ZZ" is the area code and "XXXXXXX" is your number.</span>
			</div>
		</div>
		<div class="form-group">
			<label for="comments" class="col-sm-4 control-label">Referred by:</label>
			<div class="col-sm-8">
				<input type="text" name="comments" id="comments" class="form-control" value="<?php //echo $customer->comments; ?>"/>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Billing Address</legend>
		<div class="form-group">
		<label for="" class="col-sm-4 control-label">Address Type</label>
			<div class="col-sm-8">
				<label class="checkbox-inline">
					<input type="checkbox" name="isresidential" id="isresidential" <?php //form_boolean_set($address->isResidential, "checked"); ?> />
					Residential address
				</label>
			</div>
		</div>
		
		<div class="form-group">
		<label for="address1" class="col-sm-4 control-label">Address line 1:</label>
			<div class="col-sm-8">
				<input type="text" name="address1" id="address1" class="form-control" value="<?php //echo $address->addr1; ?>" />
			</div>
		</div>
		<div class="form-group">
		<label for="address2" class="col-sm-4 control-label">Address line 2:</label>
			<div class="col-sm-8">
				<input type="text" name="address2" id="address2" class="form-control" value="<?php //echo $address->addr2; ?>" />
			</div>
		</div>
		<div class="form-group">
		<label for="city" class="col-sm-4 control-label">City:</label>
			<div class="col-sm-8">
				<input type="text" name="city" id="city" class="form-control" value="<?php //echo $address->city; ?>"/>
			</div>
		</div>
		<div class="form-group">
		<label for="state" class="col-sm-4 control-label">State:</label>
			<div class="col-sm-8">
				<input type="text" name="state" id="state" class="form-control" value="<?php //echo $address->state; ?>"/>
			</div>
		</div>
		<div class="form-group">
		<label for="zip" class="col-sm-4 control-label">Postal code:</label>
			<div class="col-sm-8">
				<input type="text" name="zip" id="zip" class="form-control" value="<?php //echo $address->zip; ?>" />
				<span style="color:#aaaaaa;font-size:80%">Please fill in "00000" if you don't know your postal code </span>
			</div>
		</div>
		<div class="form-group">
		<label for="country" class="col-sm-4 control-label">Country:</label>
			<div class="col-sm-8">
			<?php 
				require_once ("NetsuiteCountries.php");
				generate_country_select($countries, "country","");
			?>
			</div>
		</div>
		<div class="form-group">
		<label for="" class="col-sm-4 control-label">Ship to the same address</label>
			<div class="col-sm-8">
				<label class="checkbox-inline">
					<input type="checkbox" name="sameshipping" id="sameshipping" value="Yes" checked="checked" />
				</label>
			</div>
		</div>
	</fieldset>
	<fieldset id="shipping_addr">
		<legend>Shipping Address</legend>
		<div class="form-group">
			<label for="defaultbilling" class="col-sm-4 control-label">Address type</label>
			<div class="col-sm-8">
				<label class="checkbox-inline">
					<input type="checkbox" name="r_isresidential" id="r_isresidential" <?php //form_boolean_set($r_address->isResidential, "checked"); ?> />
					Residential address
				</label>
			</div>
		</div>
		<div class="form-group">
			<label for="r_address1" class="col-sm-4 control-label">Address line 1:</label>
			<div class="col-sm-8">
				<input type="text" name="r_address1" id="r_address1" class="form-control" value="<?php //echo $r_address->addr1; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_address2" class="col-sm-4 control-label">Address line 2:</label>
			<div class="col-sm-8">
				<input type="text" name="r_address2" id="r_address2" class="form-control" value="<?php //echo $r_address->addr2; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_city" class="col-sm-4 control-label">City:</label>
			<div class="col-sm-8">
				<input type="text" name="r_city" id="r_city" class="form-control" value="<?php //echo $r_address->city; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_state" class="col-sm-4 control-label">State:</label>
			<div class="col-sm-8">
				<input type="text" name="r_state" id="r_state" class="form-control" value="<?php //echo $r_address->state; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_zip" class="col-sm-4 control-label">Postal code:</label>
			<div class="col-sm-8">
				<input type="text" name="r_zip" class="form-control" id="r_zip" value="<?php //echo $r_address->zip; ?>" />
				<span style="color:#aaaaaa;font-size:80%">Please fill in "00000" if you don't know your postal code </span>
			</div>
		</div>
		<div class="form-group">
			<label for="r_country" class="col-sm-4 control-label">Country:</label>
			<div class="col-sm-8">
				<?php 
					require_once ("NetsuiteCountries.php");
					generate_country_select($countries, "r_country","");
				?>
			</div>
		</div>
	</fieldset>
	<div class="form-group">
		<div class="col-md-4 col-md-offset-4">
			<input type="hidden" class="btn btn-wcs-default" name="source" value="register.php" />
			<input type="submit" class="btn btn-wcs-default" value="Register" />
			<a href="portal.php" class="btn btn-link">Back</a>
		</div>
	</div>
</form>

<!-- Client side validation -->
<script type="text/javascript" src="lib/jquery.validate.js"></script>
<script type="text/javascript" src="lib/wcs_validation.js"></script>
<?php 
	include("templates/footer_tag.php");
?>