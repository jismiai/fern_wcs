<?php
//require_once 'controllers/log_control.php';

//handle get headers
$get_caseType = (isset($_GET["type"]))? $_GET["type"] : "";
$get_caseSubType = (isset($_GET["subtype"]))? $_GET["subtype"] : "";
$get_showForm = (isset($_GET["type"]) && isset($_GET["subtype"])) ? true : false;

include("templates/head_tag.php");
include_once ("functions/case/case_lists.php");
$caseObj = new netsuiteCase();
?>

<?php if (!($get_showForm)){ ?>
<div class="panel panel-default">
	<div class="panel-heading">Online Cases</div>
	<div class="panel-body">What would you like to do?</div>
	<ul class="list-group">
		<li class="list-group-item">Order non-shirt items</li>
		<li class="list-group-item" ><a href="case.php?type=2&subtype=1">Request swatches and catalogues</a></li>
		<li class="list-group-item">Check order progress</li>
		<li class="list-group-item">Complaint/Alteration Request</li>
		<li class="list-group-item">General Inquiry</li>
		<li class="list-group-item"><a href="portal.php" class="btn btn-link">Back</a></li>
		
	</ul>
</div>
<?php } else {?>
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
			<label for="case_type" class="col-sm-4 control-label">Case Type:</label>
			<div class="col-sm-8">
				<?php $caseObj->caseTypeHTML($get_caseType); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="case_type" class="col-sm-4 control-label">Case Sub-type:</label>
			<div class="col-sm-8">
				<?php $caseObj->subTypeHTML($get_caseType,$get_caseSubType); ?>
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
<?php } ?>

<?php 
include("templates/footer_tag.php");
?>