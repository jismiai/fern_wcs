
<?php
require_once 'controllers/log_control.php';
require_once 'PHPToolkit/NetSuiteService.php';

/* -- A service is called to load the profile of the customer -- 
 * $customer stores the output 
 * */
$service = new NetSuiteService();
$request = new GetRequest();
$request->baseRef = new RecordRef();
$request->baseRef->internalId = $_SESSION["internalid"];
$request->baseRef->type = "customer";
$getResponse = $service->get($request);


if (!$getResponse->readResponse->status->isSuccess) {
	echo "GET ERROR";
} else {
	//assign customer details into $customer
	$customer = $getResponse->readResponse->record;
	//assign address details into $address
	$addressBookListArray = $customer->addressbookList->addressbook;
	if (is_array($addressBookListArray)) { // handler if the customer has multiple addresses
		foreach($addressBookListArray as $value){
			if ($value->label == "Main Address")
				$address = $value;
			if ($value->label == "Alternative Address")
				$r_address = $value;
		}
	} else { //single address handling
		$address = $addressBookListArray;
	}
}
/* -- finished reading customer info via Webservice -- */

/* -- Form pre-set based on customer information --
	 * 	This part is important especial for checkbox, radio 
	 *  Fields applied : defaultShipping, defaultBilling, isResidential
	 */
	
	/* -- This function is  transferrable --*/
	function form_boolean_set($source, $output) {
		//$source should be a boolean data, $output is string
		$text = ($source == true) ? $output : '';
		echo $text;
	}
/* -- end of block */
	
include("templates/head_tag.php");
?>
<h2>Your Profile</h2>
<form class="form-horizontal" role="form" id="wcsform" action="controllers/update_profile.php" method="post">
	<fieldset>
		<legend>Personal Information</legend>
		<div class="form-group">
			<label for="salutation" class="col-sm-4 control-label">Salutation: </label>
			<div class="col-sm-8">
				<label class="radio-inline">
					<input type="radio" name="salutation" id="salutation_mr" class="radio_box" value="Mr." >Mr.
				</label>
				<label class="radio-inline">
					<input type="radio" name="salutation" id="salutation_mr" class="radio_box" value="Ms." >Ms.
				</label>
				<label class="radio-inline">
					<input type="radio" name="salutation" id="salutation_mr" class="radio_box" value="Mrs." >Mrs.
				</label>
			</div>
		</div>
		<script>
			//Use Javascript to update the radio boxes base on Netsuite record
			$(document).ready(function(){
				//$('radio#
				$('input.radio_box[value="<?php echo $customer->salutation; ?>"]').prop("checked",true);
			});
		</script>
		<div class="form-group">
			<label for="firstname" class="col-sm-4 control-label">First & Middle Name:</label>
			<div class="col-sm-8">
				<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $customer->firstName; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="lastname" class="col-sm-4 control-label">Last Name:</label>
			<div class="col-sm-8">
				<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $customer->lastName; ?>" />
			</div>
		</div>
		<div class="form-group">
		<label for="companyname" class="col-sm-4 control-label">Company Name:</label>
			<div class="col-sm-8">
				<input type="text" name="companyname" id="companyname" class="form-control" value="<?php echo $customer->companyName; ?>" />
			</div>	
		</div>
		<div class="form-group">
		<label for="phone" class="col-sm-4 control-label">Telephone Number</label>
			<div class="col-sm-8">
				<input type="text" name="phone" id="phone" class="form-control" value="<?php echo $customer->phone; ?>" />
				<span style="color:#aaaaaa;font-size:80%">Suggested format : +YYY-ZZ-XXXXXXX, where "YYY" is the country code, "ZZ" is the area code and "XXXXXXX" is your number.</span>
			</div>
		</div>
		<div class="form-group">
			<label for="comments" class="col-sm-4 control-label">Referred by:</label>
			<div class="col-sm-8">
				<input type="text" name="comments" id="comments" class="form-control" value="<?php echo $customer->comments; ?>"/>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Address 1</legend>
		<div class="form-group">
		<label for="" class="col-sm-4 control-label">Address Type</label>
			<div class="col-sm-8">
				<label class="radio-inline">
					<input type="radio" name="defaultbilling" id="defaultbilling" value="address" <?php form_boolean_set($address->defaultBilling, "checked"); ?> />
					Default billing address
				</label>
				<label class="radio-inline">
					<input type="radio" name="defaultshipping" id="defaultshipping" value="address" <?php form_boolean_set($address->defaultShipping, "checked"); ?> />
					Default shipping address
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" name="isresidential" id="isresidential" <?php form_boolean_set($address->isResidential, "checked"); ?> />
					Residential address
				</label>
			</div>
		</div>
		
		<div class="form-group">
		<label for="address1" class="col-sm-4 control-label">Address line 1:</label>
			<div class="col-sm-8">
				<input type="text" name="address1" id="address1" class="form-control" value="<?php echo $address->addr1; ?>" />
			</div>
		</div>
		<div class="form-group">
		<label for="address2" class="col-sm-4 control-label">Address line 2:</label>
			<div class="col-sm-8">
				<input type="text" name="address2" id="address2" class="form-control" value="<?php echo $address->addr2; ?>" />
			</div>
		</div>
		<div class="form-group">
		<label for="city" class="col-sm-4 control-label">City:</label>
			<div class="col-sm-8">
				<input type="text" name="city" id="city" class="form-control" value="<?php echo $address->city; ?>"/>
			</div>
		</div>
		<div class="form-group">
		<label for="state" class="col-sm-4 control-label">State:</label>
			<div class="col-sm-8">
				<input type="text" name="state" id="state" class="form-control" value="<?php echo $address->state; ?>"/>
			</div>
		</div>
		<div class="form-group">
		<label for="zip" class="col-sm-4 control-label">Postal code:</label>
			<div class="col-sm-8">
				<input type="text" name="zip" id="zip" class="form-control" value="<?php echo $address->zip; ?>" />
				<span style="color:#aaaaaa;font-size:80%">Please fill in "00000" if you don't know your postal code </span>
			</div>
		</div>
		<div class="form-group">
		<label for="country" class="col-sm-4 control-label">Country:</label>
			<div class="col-sm-8">
			<?php 
				require_once ("NetsuiteCountries.php");
				generate_country_select($countries, "country",$address->country);
			?>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Address 2</legend>
		<div class="form-group">
			<label for="defaultbilling" class="col-sm-4 control-label">Address type</label>
			<div class="col-sm-8">
				<label class="radio-inline">
					<input type="radio" name="defaultbilling" id="r_defaultbilling" value="r_address" <?php form_boolean_set($r_address->defaultBilling, "checked"); ?> />
					Default billing address
				</label>
				<label class="radio-inline">
					<input type="radio" name="defaultshipping" id="r_defaultshipping" value="r_address" <?php form_boolean_set($r_address->defaultShipping, "checked"); ?> />
					Default shipping address
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" name="r_isresidential" id="r_isresidential" <?php form_boolean_set($r_address->isResidential, "checked"); ?> />
					Residential address
				</label>
			</div>
		</div>
		<div class="form-group">
			<label for="r_address1" class="col-sm-4 control-label">Address line 1:</label>
			<div class="col-sm-8">
				<input type="text" name="r_address1" id="r_address1" class="form-control" value="<?php echo $r_address->addr1; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_address2" class="col-sm-4 control-label">Address line 2:</label>
			<div class="col-sm-8">
				<input type="text" name="r_address2" id="r_address2" class="form-control" value="<?php echo $r_address->addr2; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_city" class="col-sm-4 control-label">City:</label>
			<div class="col-sm-8">
				<input type="text" name="r_city" id="r_city" class="form-control" value="<?php echo $r_address->city; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_state" class="col-sm-4 control-label">State:</label>
			<div class="col-sm-8">
				<input type="text" name="r_state" id="r_state" class="form-control" value="<?php echo $r_address->state; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label for="r_zip" class="col-sm-4 control-label">Postal code:</label>
			<div class="col-sm-8">
				<input type="text" name="r_zip" class="form-control" id="r_zip" value="<?php echo $r_address->zip; ?>" />
				<span style="color:#aaaaaa;font-size:80%">Please fill in "00000" if you don't know your postal code </span>
			</div>
		</div>
		<div class="form-group">
			<label for="r_country" class="col-sm-4 control-label">Country:</label>
			<div class="col-sm-8">
				<?php 
					require_once ("NetsuiteCountries.php");
					generate_country_select($countries, "r_country",$r_address->country);
				?>
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="source" value="profile.php" />
	<div class="form-group" style="padding:20px 0;">
		<div class="col-sm-offset-4 col-sm-4">
		<button type="submit" value="Update" class="btn btn-wcs-default" />Update</button>
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