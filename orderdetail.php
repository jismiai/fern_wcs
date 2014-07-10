<?php
require_once 'controllers/log_control.php';
include_once "config.php"; // to get the local url;
include_once $documentroot."/PHPToolkit/NSconfig.php";
if (isset($_POST["so_internalid"])){
	//echo $_POST["so_internalid"]."<br />";
	//call suitelet
	$url = 'https://rest.netsuite.com/app/site/hosting/restlet.nl?script=246&deploy=1';
	$postContent = array("custid" => $_SESSION["customerID"], "so_internalid" => $_POST["so_internalid"]);
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
	//echo"<pre>";
	//var_dump($salesOrder->item);
	//echo"</pre>";
	//output if success
	//output general information
	
} else {
	header('Location:'.$localurl."error.php?error_code=others");
	exit;
}
?>
<?php include("templates/head_tag.php"); ?>
<table class="table table-bordered">
	<caption>Order Information</caption>
	<tbody>
		<tr><th>Sales Order no.</th><td><?php echo $salesOrder->tranid; ?></td></tr>
		<tr><th>Sales Order date</th><td><?php echo $salesOrder->trandate; ?></td></tr>
		<tr><th>Status</th><td><?php echo $salesOrder->status; ?></td></tr>
	</tbody>
</table>
<table id="ordered-item" class="table table-striped">
	<caption>Ordered Items</caption>
	<thead>
		<th>Product</th>
		<th>Quantity</th>
		<th>Fabric</th>
		<th>Style</th>
	</thead>
	<tbody>
	<?php foreach ($salesOrder->item as $currentLineItem){
		if ($currentLineItem !== null){
			if ($currentLineItem->item->itemtype == "InvtPart"){?>
		<tr>
			<td><?php echo $currentLineItem->description; ?></td>
			<td><?php echo $currentLineItem->quantity; ?></td>
			<td><?php echo $currentLineItem->custcolfabric->name; ?></td>
			<td>
				<?php
					$cellOutput = "";
					$separator = ", ";
					if ($currentLineItem->item->custitemfinalgoodtype == "Shirt"){
						if (!empty($currentLineItem->custcolstyle->fields->collar)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->collar.$separator;
						} else {echo "Wrong here!!";}
						if (!empty($currentLineItem->custcolstyle->fields->front)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->front.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->cuff)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->cuff.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->pocket)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->pocket.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->button)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->button.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->backPleat)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->backPleat.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->fitting)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->fitting.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->monoIni)){
							$cellOutput .= "<br />Monogram: ".$currentLineItem->custcolstyle->fields->monoIni;
							if (!empty($currentLineItem->custcolstyle->fields->monoPos)){
								$cellOutput .= $currentLineItem->custcolstyle->fields->monoPos;
							}
							if (!empty($currentLineItem->custcolstyle->fields->monoColor)){
								$cellOutput .= $currentLineItem->custcolstyle->fields->monoColor;
							}
						}
					} else {
						if (!empty($currentLineItem->custcolstyle->fields->jacketFront)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->jacketFront.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->lapelStyle)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->lapelStyle.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->vent)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->vent.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->lowerPocket)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->lowerPocket.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->jacketCuff)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->jacketCuff.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->jacketFitting)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->jacketFitting.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->trousersFront)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->trousersFront.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->waistBand)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->waistBand.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->trousersFrontPocket)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->trousersFrontPocket.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->trousersRearPocket)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->trousersRearPocket.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->trousersBottom)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->trousersBottom.$separator;
						}
						if (!empty($currentLineItem->custcolstyle->fields->trousersFitting)){
							$cellOutput .= $currentLineItem->custcolstyle->fields->trousersFitting.$separator;
						}
						
					}
					echo trim($cellOutput,$separator);
				?>
			</td>
		</tr>
	<?php 
			}
		}
	} 
	?>
	</tbody>
</table>

<table id="altered-item" class="table table-striped">
	<caption>Alterated Items</caption>
	<thead>
		<th>Product</th>
		<th>Quantity</th>
		<th>Fabric</th>
	</thead>
	<tbody>
	<?php foreach ($salesOrder->item as $currentLineItem){
		if ($currentLineItem !== null){
			if ($currentLineItem->item->itemtype == "NonInvtPart"){?>
		<tr>
			<td><?php echo $currentLineItem->item->name; ?></td>
			<td><?php echo $currentLineItem->quantity; ?></td>
			<td><?php echo $currentLineItem->custcolfabric->name; ?></td>
		</tr>
			<?php }
		}
	}?>
	</tbody>
</table>
<a href="orderstatus.php" class="btn">Back to summary</a>
<script>
$(document).ready(function(){ // remove tables if no items is given on that table.
	var orderLine = $("#ordered-item tbody tr").length;
	var alterLine = $("#altered-item tbody > tr").length;
	if (orderLine == 0){
		$("#ordered-item").remove();
	}
	if (alterLine == 0){
		$("#altered-item").remove();
	}
	//alert("no of ordered item = "+orderLine);
});

</script>
<?php 
	include("templates/footer_tag.php");
?>