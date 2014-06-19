<?php
session_start();
include_once "../config.php"; // to get the local url;
include_once $documentroot."/PHPToolkit/NSconfig.php";

if (isset($_POST)){
//	echo "<pre>";	print_r($_POST); echo "</pre>";
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
			if (isset($_POST["product_type"][$key]) && !empty($_POST["product_type"][$key])){
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
		break;
	case "1":
		if ($_POST["fabric_material"] == "Others"){
			$_POST["fabric_material"] = $_POST["fabric_material_other"];
		}
		if (array_search('Others',$_POST["fabric_color"]) !== false){ //handle multiselect & other input
			$fabric_color_other = ":{$_POST['fabric_color_other']}";
		}
		$caseObj->custevent_case_product_type = $_POST["product_type"];
		$caseObj->custevent_case_swatches_color = implode(", ", $_POST["fabric_color"]).$fabric_color_other;
		$caseObj->custevent_case_swatches_pattern = implode(", ", $_POST["fabric_pattern"]);
		$caseObj->custevent_case_swatches_material = $_POST["fabric_material"];
		$caseObj->custevent_case_swatches_brand = $_POST["fabric_brand"];
		$caseObj->custevent_case_swatch_type = $_POST["swatch_type"];
		$caseObj->custevent_case_other_desc = $_POST["other"];
		$caseObj->custevent_case_catalogue_mail_address = $_POST["catalogue_address"];
		$caseObj->custevent_case_catalogue_name = $_POST["catalogue_name"];
		break;
	case "14":
		$caseObj->custeventcasedetails = $_POST["order_no"];
		$caseObj->custevent_case_other_desc = $_POST["other"];
		break;
	case "18":
		$caseObj->custeventcasedetails = $_POST["case_detail"];
		break;
	case "15":
		$curLine = 0;
		foreach ($_POST["product_type"] as $key => $value){
			if (isset($_POST["product_type"][$key]) && !empty($_POST["product_type"][$key])){
		
				$caseObj->complaint_item[$curLine] = new stdClass();
				$caseObj->complaint_item[$curLine]->custrecord_com_product = $_POST["product_type"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_quantity = $_POST["quantity"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_problem_part = $_POST["problem_part"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_mea_change = $_POST["complaint_mea_change"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_others = $_POST["other"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_so_num = $_POST["complaint_so"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_is_company_product = (isset($_POST["is_company"][$key]) ? "T" : "F");
				$curLine++;
			}
		}
		break;
	case "21":
		$caseObj->custevent_case_complaint_type = $_POST["complaint_type"];
		$caseObj->custevent_case_request = $_POST["complaint_request"];
		$caseObj->custevent_case_complaint_detail = $_POST["complaint_detail"];
		$caseObj->complaint_item = array();
		$curLine = 0;
		foreach ($_POST["product_type"] as $key => $value){
			if (isset($_POST["product_type"][$key]) && !empty($_POST["product_type"][$key])){
				
				$caseObj->complaint_item[$curLine] = new stdClass();
				$caseObj->complaint_item[$curLine]->custrecord_com_product = $_POST["product_type"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_quantity = $_POST["quantity"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_problem_part = $_POST["problem_part"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_mea_change = $_POST["complaint_mea_change"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_others = $_POST["other"][$key];
				$caseObj->complaint_item[$curLine]->custrecord_com_so_num = $_POST["complaint_so"][$key];
				$curLine++;
			}
		}
		break;
	case "22":
		//webservice to upload to Netsuite.
		//check file type
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["photo"]["name"]);
		$extension = end($temp);
		if ((($_FILES["photo"]["type"] == "image/gif")
				|| ($_FILES["photo"]["type"] == "image/jpeg")
				|| ($_FILES["photo"]["type"] == "image/jpg")
				|| ($_FILES["photo"]["type"] == "image/pjpeg")
				|| ($_FILES["photo"]["type"] == "image/x-png")
				|| ($_FILES["photo"]["type"] == "image/png"))
				&& ($_FILES["photo"]["size"] < 2000000)
				&& in_array($extension, $allowedExts)) {
			require_once '../PHPToolkit/NetSuiteService.php';
			$imgPath = $_FILES["photo"]["tmp_name"]; //specify the file path
			//$imgContents = base64_encode(file_get_contents($imgPath)); //get the contents of the file
			$imgContents = file_get_contents($imgPath);
			$file = new File();
			$file->folder = new RecordRef();
			$file->folder->internalId = "36339";
			date_default_timezone_set("Asia/Hong_Kong");
			$file->name = $_SESSION["entityID"]."_".date('Ymd')."_".$_FILES["photo"]["name"];
			$file->attachFrom = '_computer';
			$file->content = $imgContents;
			$service = new NetSuiteService();
			$addRequest = new AddRequest();
			$addRequest->record = $file;
			$addResponse = $service->add($addRequest);
			if (!$addResponse->writeResponse->status->isSuccess) {
				header("location:".$localurl."error.php?error_code=file_type&source=1");
				exit;
			} else {
				$file_id = $addResponse->writeResponse->baseRef->internalId;
				echo "ADD FILE SUCCESS, id " . $addResponse->writeResponse->baseRef->internalId;
			}
		} else { //invalid file type 
			header("location:".$localurl."error.php?error_code=file_type&source=2");
			//echo "wrong file type;";
			exit;
		}
		$caseObj->custevent_case_chmeas_increase_decrease = $_POST["weight_increase"];
		$caseObj->custevent_case_chmeas_weight_change = $_POST["weight_change_kg"];
		$caseObj->custevent_case_chmeas_change_neck = (isset($_POST["change_neck"]) ? "T" : "F");
		$caseObj->custevent_case_chmeas_neck_inch = (isset($_POST["change_neck"]) ? $_POST["neck_inch"] : "");
		$caseObj->custevent_case_chmeas_change_chest = (isset($_POST["change_chest"]) ? "T" : "F");
		$caseObj->custevent_case_chmeas_chest_inch = (isset($_POST["change_chest"]) ? $_POST["chest_inch"] : "");
		$caseObj->custevent_case_chmeas_change_waist = (isset($_POST["change_waist"]) ? "T" : "F");
		$caseObj->custevent_case_chmeas_waist_inch = (isset($_POST["change_waist"]) ? $_POST["waist_inch"] : "");
		$caseObj->custevent_case_chmeas_change_hip = (isset($_POST["change_hip"]) ? "T" : "F");
		$caseObj->custevent_case_chmeas_hip_inch = (isset($_POST["change_hip"]) ? $_POST["hip_inch"] : "");
		$caseObj->custevent_case_chmeas_upload_photo = $file_id;
		break;
}
//echo "<pre>";	var_dump($caseObj); echo "</pre>";

$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=252&deploy=1';
//var_dump($postContent);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json','Authorization: NLAuth nlauth_account='.$nsaccount.',nlauth_email='.$nsemail.',nlauth_signature='.$nspassword.',nlauth_role='.$nsrole));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($caseObj));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK
$response = curl_exec($ch);
//echo "<pre>";var_dump(json_decode($response));echo "</pre>";
$response = json_decode($response);
if ($response->isSuccess && !empty($response->casenumber)){
	header("location:".$localurl."success.php?source=supportcase&detail={$response->casenumber}");
	exit;
}
echo "System error. Please contact our staff for assistance.";

?>