<?php

require_once 'controllers/log_control.php';
include_once "config.php"; // to get the local url;
include_once $documentroot."/PHPToolkit/NSconfig.php";

//handle get headers
$get_caseType = (isset($_GET["type"]))? $_GET["type"] : "";
$get_caseSubType = (isset($_GET["subtype"]))? $_GET["subtype"] : "";
$get_showForm = (isset($_GET["type"]) && isset($_GET["subtype"])) ? true : false;


include_once ("functions/case/case_lists.php");
$caseObj = new netsuiteCase();
$caseObj->setCaseType($get_caseType);
$caseObj->setSubType($get_caseSubType);

//get customer information
if ($get_showForm){
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
}

include("templates/head_tag.php");

echo "<pre>";
var_dump(json_encode($postContent));var_dump($response);echo "</pre>";
$customer = json_decode($response);

?>

<?php if (!($get_showForm)){ ?>
<div class="panel panel-default">
	<div class="panel-heading">Online Cases</div>
	<div class="panel-body">What would you like to do?</div>
	<ul class="list-group">
		<li class="list-group-item"><a href="case.php?type=2&subtype=13">Order non-shirt items</a></li>
		<li class="list-group-item" ><a href="case.php?type=2&subtype=1">Request swatches and catalogues</a></li>
		<li class="list-group-item">Check order progress</li>
		<li class="list-group-item">Complaint/Alteration Request</li>
		<li class="list-group-item">General Inquiry</li>
		<li class="list-group-item"><a href="portal.php" class="btn btn-link">Back</a></li>
		
	</ul>
</div>
<?php } else {?>
<form name="caseform" action="controllers/set_case.php" id="caseform" class="form-horizontal" role="form" method="post">
	<fieldset>
		<legend>General information</legend>
		<div class="form-group">
			<label for="customer_id" class="col-sm-4 control-label">Customer ID:</label>
			<div class="col-sm-8">
				<input type="text" name="customer_id" class="form-control" id="customer_id" value="<?php echo $customer->entityid; ?>" readonly/>
			</div>
		</div>
		<div class="form-group">
			<label for="customer_name" class="col-sm-4 control-label">Customer Name:</label>
			<div class="col-sm-8">
				<input type="text" name="customer_name" class="form-control" id="customer_name" value="<?php echo $customer->name; ?>" readonly/>
			</div>
		</div>
		<div class="form-group">
			<label for="customer_email" class="col-sm-4 control-label">Email:</label>
			<div class="col-sm-8">
				<input type="email" name="customer_email" class="form-control" id="customer_email" value="<?php echo $customer->email; ?>" readonly/>
			</div>
		</div>
		<div class="form-group">
			<label for="customer_phone" class="col-sm-4 control-label">Contact No.</label>
			<div class="col-sm-8">
				<input type="text" name="customer_phone" class="form-control" id="customer_phone" value="<?php echo $customer->phone; ?>" readonly/>
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
		<div class="form-group">
			<label for="case_subject" class="col-sm-4 control-label">Subject:</label>
			<div class="col-sm-8">
				<input type="text" name="case_subject" class="form-control" id="case_subject" value="" />
			</div>
		</div>
	</fieldset>
	<?php 
	//* lower parts of the form depends on the type *//
	switch($caseObj->subTypeId){ 
		case "13": // "case place order"?>
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
		<?php break; //end of case "13"?>
		
		<?php case "1": // case : "request swatch / catalogue"?>
		<fieldset>
		<legend>Request for swatch</legend>
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Product Type:</label>
			<div class="col-sm-8">
				<?php echo $caseObj->productTypeHTML(''); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Fabric brand:</label>
			<div class="col-sm-8">
				<?php echo $caseObj->fabricBrandHTML(); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Fabric color:</label>
			<div class="col-sm-8">
				<?php echo $caseObj->fabricColorHTML(); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Fabric pattern:</label>
			<div class="col-sm-8">
				<?php echo $caseObj->fabricPatternHTML(); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Fabric materials:</label>
			<div class="col-sm-8">
				<?php echo $caseObj->fabricMaterialHTML(); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="other" class="col-sm-4 control-label">Other requirements:</label>
			<div class="col-sm-8">
				<textarea rows="3" name="other" class="form-control"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="swatch_type" class="col-sm-4 control-label">Swatch type:</label>
			<div class="col-sm-8">
				<label class="radio-inline">
					<input type="radio" name="swatch_type" id="swatch_type_image" class="radio_box" value="Image" >Image
				</label>
				<label class="radio-inline">
					<input type="radio" name="swatch_type" id="swatch_type_fabric" class="radio_box" value="Fabric" >Fabric
				</label>
			</div>
		</div>
		<div class="form-group">
			<label for="swatch_address" class="col-sm-4 control-label">Mailing Address:</label>
			<div class="col-sm-8">
				<textarea rows="3" name="swatch_address" class="form-control"></textarea>
			</div>
		</div>
		</fieldset>
		<fieldset>
		<legend>Request for catalogue</legend>
		<div class="form-group">
			<label for="catalogue_name" class="col-sm-4 control-label">Mailing Address:</label>
			<div class="col-sm-8">
				<select name="catalogue_name" class="form-control">
					<option value="">Please select a catalogue</option>
					<option>Suit catalogue</option>
					<option>Shirt catalogue</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="catalogue_address" class="col-sm-4 control-label">Mailing Address:</label>
			<div class="col-sm-8">
				<textarea rows="3" name="catalogue_address" class="form-control"></textarea>
			</div>
		</div>
		</fieldset>
		<?php break; //end of case "1"?>
	<?php } //end of switch statment?>
	
	<div class="form-group" style="padding:20px 0;">
		<div class="col-sm-offset-4 col-sm-4" >
			<button type="submit" class="btn btn-wcs-default">Submit</button>
			<a href="case.php" class="btn btn-link">Back</a>
		</div>
	</div>
</form>
<?php } ?>
<script type="text/javascript" src="lib/jquery.validate.js"></script>
<script type="text/javascript" src="lib/wcs_validation.js"></script>
<?php echo $caseObj->caseJavascript();?>
<?php 
include("templates/footer_tag.php");
?>