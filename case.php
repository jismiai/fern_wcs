<?php
require_once 'controllers/log_control.php';

//if 

include("templates/head_tag.php");
?>

<div class="panel panel-default">
	<div class="panel-heading">Online Cases</div>
	<div class="panel-body">What would you like to do?</div>
	<ul class="list-group">
		<li class="list-group-item">Order non-shirt items</li>
		<li class="list-group-item">Request swatches and catalogues</li>
		<li class="list-group-item">Check order progress</li>
		<li class="list-group-item">Complaint/Alteration Request</li>
		<li class="list-group-item">General Inquiry</li>
	</ul>
</div>
<form name="case_form" id="case_form" class="form-horizontal" role="form" method="post">
	<fieldset>
		<legend>General information</legend>
		<div class="form-group">
			<label for="customer_id" class="col-sm-4 control-label">Customer ID:</label>
			<div class="col-sm-8">
				<input type="text" name="customer_id" class="form-control" id="customer_id" />
			</div>
		</div>
		<div class="form-group">
			<label for="customer_name" class="col-sm-4 control-label">Customer Name:</label>
			<div class="col-sm-8">
				<input type="text" name="customer_name" class="form-control" id="customer_name" />
			</div>
		</div>
		<div class="form-group">
			<label for="customer_email" class="col-sm-4 control-label">Email:</label>
			<div class="col-sm-8">
				<input type="email" name="customer_email" class="form-control" id="customer_email" />
			</div>
		</div>
		<div class="form-group">
			<label for="customer_phone" class="col-sm-4 control-label">Contact No.</label>
			<div class="col-sm-8">
				<input type="email" name="customer_phone" class="form-control" id="customer_phone" />
			</div>
		</div>
		<div class="form-group">
		<div class="col-md-4 col-md-offset-4">
			<input type="submit" class="btn btn-wcs-default" value="Submit" />
			<a href="portal.php" class="btn btn-link">Back</a>
		</div>
	</div>
	</fieldset>
</form>

<?php 
include("templates/footer_tag.php");
?>