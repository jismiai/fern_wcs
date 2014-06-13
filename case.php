<?php

require_once 'controllers/log_control.php';
include_once "config.php"; // to get the local url;
include_once $documentroot."/PHPToolkit/NSconfig.php";

//handle get headers
$get_caseType = (isset($_GET["type"]))? $_GET["type"] : "";
$get_caseSubType = (isset($_GET["subtype"]))? $_GET["subtype"] : "";
$get_showForm = (isset($_GET["type"]) && isset($_GET["subtype"])) ? true : false;

//get customer information
$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=251&deploy=1';
	$postContent = array("custid" => $_SESSION["customerID"]);
	//var_dump($postContent);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json','Authorization: NLAuth nlauth_account='.$nsaccount.',nlauth_email='.$nsemail.',nlauth_signature='.$nspassword.',nlauth_role='.$nsrole));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postContent));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK
	$response = curl_exec($ch);


include("templates/head_tag.php");
include_once ("functions/case/case_lists.php");
$caseObj = new netsuiteCase();

echo "<pre>";var_dump($nsaccount);
var_dump(json_encode($postContent));var_dump($response);echo "</pre>";

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
			<label for="case_subtype" class="col-sm-4 control-label">Case Sub-type:</label>
			<div class="col-sm-8">
				<?php $caseObj->subTypeHTML($get_caseType,$get_caseSubType); ?>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<div id="order_req_list">
			<?php echo $caseObj->orderReqLineStr();?>
		</div>
		<div class="form-group" style="">
			<div class="col-sm-offset-4 col-sm-4" >
				<button class="btn" id="add_order_row">Add more</button>
			</div>
		</div>
	</fieldset>
	<div class="form-group" style="padding:20px 0;">
		<div class="col-sm-offset-4 col-sm-4" >
			<button type="submit" class="btn btn-wcs-default">Submit</button>
			<a href="case.php" class="btn btn-link">Back</a>
		</div>
	</div>
</form>
<?php } ?>
<script>
$(document).ready(function(){
	$('#add_order_row').click(function(){
		var tblrow = '<?php echo $caseObj->orderReqLineStr(); ?>';
		$('#order_req_list').append(tblrow);
		$('.order_req_wrapper:last').slideDown('slow');
		return false;
	});
});
</script>
<?php 
include("templates/footer_tag.php");
?>