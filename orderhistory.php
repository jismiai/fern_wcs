<?php
require_once 'controllers/log_control.php';
include_once "config.php"; // to get the local url;
include_once $documentroot."/PHPToolkit/NSconfig.php";

//read the historical orders, suitelet
$url = 'https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=257&deploy=1&compid=3716988&h=b960af885d559a3bdc51';
$postContent = array("custid" => $_SESSION["customerID"]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK

$response = curl_exec($ch);
//JSON response is decoded and saved in $orders
$orders = json_decode($response);
$OrderIndex = 0;
$OrderLineNo = 1;
$maxLine = 20;

include("templates/head_tag.php"); 
?>
<h3>Order History</h3>
<table id="ordered-item" class="table table-striped">
	<thead>
		<th>Sales order date</th>
		<th>Sales order no.</th>
		<th>Product</th>
		<th>Quantity</th>
		<th>Fabric</th>
	</thead>
	<tbody>
<?php 
while ($OrderLineNo <= 20 && $OrderIndex < count($orders)){
	//open a order
	
	$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=246&deploy=1';
	$postContent = array("custid" => $_SESSION["customerID"], "so_internalid" => $orders[$OrderIndex]->internalid);
	//var_dump($postContent);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json','Authorization: NLAuth nlauth_account='.$nsaccount.',nlauth_email='.$nsemail.',nlauth_signature='.$nspassword.',nlauth_role='.$nsrole));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postContent));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK
	$response = curl_exec($ch);
	//JSON response is decoded and saved in $invoices
	$salesOrder = json_decode($response);
	//forloop display a order's detail, break if necessary
	foreach ($salesOrder->item as $currentLineItem){
		if ($currentLineItem !== null){
			if ($currentLineItem->item->itemtype == "InvtPart"){?>
			<tr>
				<td><?php echo $salesOrder->trandate; ?></td>
				<td><?php echo $salesOrder->tranid; ?></td>
				<td><?php echo $currentLineItem->description; ?></td>
				<td><?php echo $currentLineItem->quantity; ?></td>
				<td><?php echo $currentLineItem->custcolfabric->name; ?></td>
			</tr>
			<?php 	}
			// if alteration
			if ($currentLineItem->item->itemtype == "NonInvtPart"){?>
			<tr>
				<td><?php echo $salesOrder->trandate; ?></td>
				<td><?php echo $salesOrder->tranid; ?></td>
				<td><?php echo $currentLineItem->item->name; ?></td>
				<td><?php echo $currentLineItem->quantity; ?></td>
				<td><?php echo $currentLineItem->custcolfabric->name; ?></td>
			</tr>
			<?php }
		}
		$OrderLineNo++;
		if ($OrderLineNo > $maxLine) break; // in case need to end early
	} 
	$OrderIndex++;
}?>
	</tbody>
</table>
<div class="">
	<a href="portal.php" class="btn btn-wcs-default btn-lg" >Back</a>
</div>
<?php 
	include("templates/footer_tag.php");
?>
