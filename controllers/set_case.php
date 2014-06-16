<?php
session_start();
include_once "../config.php"; // to get the local url;
include_once $documentroot."/PHPToolkit/NSconfig.php";

if (isset($_POST)){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	
}
$caseObj = new stdClass();
$caseObj->custid = $_SESSION["customerID"];
$caseObj->case_type = $_POST["case_type"];
$caseObj->case_subtype = $_POST["case_subtype"];
$caseObj->email = $_POST["customer_email"];
$caseObj->phone = $_POST["customer_phone"];
$caseObj->title = $_POST["case_subject"];
switch($caseObj->case_subtype){
	case "13":
		$caseObj->order_req = array();
		$curLine = 0;
		foreach ($_POST["product_type"] as $key => $value){
			if (isset($_POST["product_type"]) && !empty($_POST["product_type"])){
				if ($_POST["fabric_color"][$key] == "Others"){
					$_POST["fabric_color"][$key] = $_POST["fabric_color_other"][$key];
				}
				if ($_POST["fabric_material"][$key] == "Others"){
					$_POST["fabric_material"][$key] = $_POST["fabric_material_other"][$key];
				}
				if (!empty($_POST["recv_date"][$key])){ //convert
					$_POST["recv_date"][$key] = date("d/m/Y", strtotime($_POST["recv_date"][$key]));
				}
				$caseObj->order_req[$curLine] = new stdClass();
				$caseObj->order_req[$curLine]->custrecord_orderreq_producttype = $_POST["product_type"][$key];
				$caseObj->order_req[$curLine]->custrecord_orderreq_quantity = $_POST["quantity"][$key];
				$caseObj->order_req[$curLine]->custrecord_orderreq_fabricbrand = $_POST["fabric_brand"][$key];
				$caseObj->order_req[$curLine]->custrecord_orderreq_fabriccolor = $_POST["fabric_color"][$key];
				$caseObj->order_req[$curLine]->custrecord_orderreq_fabric_pattern = $_POST["fabric_pattern"][$key];
				$caseObj->order_req[$curLine]->custrecord_orderreq_fabricmaterial = $_POST["fabric_material"][$key];
				$caseObj->order_req[$curLine]->custrecord_orderreq_otherreq = $_POST["other"][$key];
				$caseObj->order_req[$curLine]->custrecord_orderreq_expdate = $_POST["recv_date"][$key];
				$curLine++;
			}
		}
	case "1":
		// declare the body field first 
		//$caseObj->producttype = $_POST["product_type"]["0"];
		$caseObj->custevent_case_product_type = $_POST["product_type"]["0"];
		$caseObj->custevent_case_swatches_color = $_POST["fabric_color"]["0"];
		$caseObj->custevent_case_swatches_pattern = $_POST["fabric_pattern"]["0"];
		$caseObj->custevent_case_swatches_material = $_POST["fabric_material"]["0"];
		$caseObj->custevent_case_swatches_brand = $_POST["fabric_brand"]["0"];
		$caseObj-> custevent_case_swatch_type = $_POST["swatch_type"];
		$caseObj->custevent_case_swatch_mail_address = $_POST["swatch_address"];
		$caseObj->custevent_case_other_desc = $_POST["other"];
		$caseObj->custevent_case_catalogue_mail_address = $_POST["catalogue_address"];
		$caseObj->custevent_case_catalogue_name = $_POST["catalogue_name"];
}

$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=252&deploy=1';
//var_dump($postContent);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json','Authorization: NLAuth nlauth_account='.$nsaccount.',nlauth_email='.$nsemail.',nlauth_signature='.$nspassword.',nlauth_role='.$nsrole));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($caseObj));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK
$response = curl_exec($ch);
echo "<pre>";
var_dump(json_decode($response));
echo "</pre>";
?>