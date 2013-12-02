
<?php
include("templates/head_tag.php");
?>
<h2>Customer Registration</h2>
<div>Fill in the information below to register</div>
<form class="form-horizontal" role="form" id="wcsform" action="controllers/new_customer.php" method="post">
	<fieldset>
		<legend>Login Detail</legend>
		<label for="user_email">Email:</label>
		<input type="email" name="user_email" id="user_email" />
		<br />
		<label for="user_password">Password:</label>
		<input type="password" name="user_password" id="user_password" />
	</fieldset>
	<fieldset>
		<legend>Personal Information</legend>
		<label for="salutation">Salutation: </label>
		<input type="radio" name="salutation" id="salutation_mr" value="Mr." checked/>Mr.
		<input type="radio" name="salutation" id="salutation_ms" value="Ms." />Ms.
		<input type="radio" name="salutation" id="salutation_mrs" value="Mrs." />Mrs.
		<br />
		<label for="firstname">First Name:</label>
		<input type="text" name="firstname" id="firstname" />
		<br />
		<label for="lastname">Last Name:</label>
		<input type="text" name="lastname" id="lastname" />
		<br />
		<label for="companyname">Company Name:</label>
		<input type="text" name="companyname" id="companyname" />
		<br />
		<label for="phone">Telephone Number</label>
		<input type="text" name="phone" id="phone" />
		<br />
		<label for="comments">Referred by:</label>
		<input type="text" name="comments" id="comments" />
		<br />
	</fieldset>
	<fieldset>
		<legend>Main Address</legend>
		<label for="defaultbilling">Default billing address</label>
		<input type="radio" name="defaultbilling" id="defaultbilling" value="address" checked="checked" />
		<br />
		<label for="defaultshipping">Default shipping address</label>
		<input type="radio" name="defaultshipping" id="defaultshipping" value="address" checked="checked" />
		<br />
		<label for="isresidential">Residental address</label>
		<input type="checkbox" name="isresidential" id="isresidential"/>
		<br />
		<label for="address1">Address line 1:</label>
		<input type="text" name="address1" id="address1" />
		<br />
		<label for="address2">Address line 2:</label>
		<input type="text" name="address2" id="address2" />
		<br />
		<label for="city">City:</label>
		<input type="text" name="city" id="city" />
		<br />
		<label for="state">State:</label>
		<input type="text" name="state" id="state" />
		<br />
		<label for="zip">Postal code:</label>
		<input type="text" name="zip" id="zip" />
		<br />
		<label for="country">Country:</label>
		<?php 
			require_once ("NetsuiteCountries.php");
			generate_country_select($countries, "country","");
		?>
		<br />
	</fieldset>
	<fieldset>
		<legend>Alternative Address</legend>
		<label for="defaultbilling">Default billing address</label>
		<input type="radio" name="defaultbilling" id="r_defaultbilling" value="r_address" />
		<br />
		<label for="defaultshipping">Default shipping address</label>
		<input type="radio" name="defaultshipping" id="r_defaultshipping" value="r_address" />
		<br />
		<label for="isresidential">Residental address</label>
		<input type="checkbox" name="r_isresidential" id="r_isresidential" />
		<br />
		<label for="r_address1">Address line 1:</label>
		<input type="text" name="r_address1" id="r_address1" />
		<br />
		<label for="r_address2">Address line 2:</label>
		<input type="text" name="r_address2" id="r_address2" />
		<br />
		<label for="r_city">City:</label>
		<input type="text" name="r_city" id="r_city" />
		<br />
		<label for="r_state">State:</label>
		<input type="text" name="r_state" id="r_state" />
		<br />
		<label for="r_zip">Postal code:</label>
		<input type="text" name="r_zip" id="r_zip" />
		<br />
		<label for="r_country">Country:</label>
		<?php 
			require_once ("NetsuiteCountries.php");
			generate_country_select($countries, "r_country","");
		?>
		<br />
	</fieldset>
	<input type="hidden" name="source" value="register.php" />
	<input type="submit" value="Register" />
</form>

<!-- Client side validation -->
<script type="text/javascript" src="lib/jquery.validate.js"></script>
<script type="text/javascript" src="lib/wcs_validation.js"></script>
<?php 
	include("templates/footer_tag.php");
?>