<?php
/***
 * Function : List the open sales order of this customer and show details
* Visit can click into particular item to drill down
* Read : Suitelet
* Output : PHP
*/

//log control
require_once 'controllers/log_control.php';


//read the suitelet JSON
$url = 'https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=259&deploy=1&compid=3716988&h=143bf02b4e5394ce7a06';
$postContent = array("custid" => $_SESSION["customerID"]);
//$postContent = array("custid" => '26546');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization' => 'NLAuth nlauth_account=3716988,nlauth_email=davidwcs@fern.com.hk,nlauth_signature=willwit78,nlauth_role=3'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK

$response = curl_exec($ch);
//JSON response is decoded and saved in $orders
$fulfillments = json_decode($response);
/*echo "<pre>";
var_dump($orders);
echo "</pre>";
*/
//update display setting if necessary

//display the list
$custom_head = '		
<script>
$(document).ready(function(){
	$(".a-markrecv").click(function(){	
		var fulfillment_id = $(this).attr("data-internalid");
		$("#fulfillment_internalid").val(fulfillment_id);
		$("#custom-form").attr("action","controllers/receiveorder.php");
		$("#custom-form").submit();
		return false;
	});
});
</script>';
include("templates/head_tag.php");

//display success msg for order receipt
if (isset($_GET["success"]) && $_GET["success"]==true){?>
	<div class="panel panel-success">
		<div class="panel-heading">
			<h2 class="panel-title">Success</h2>
		</div>
		<div class="panel-body">
			<p>Order received.</p>
		</div>
	</div>
<?php } ?>

<h3>Orders</h3>
<table class="table table-hover">
	<thead>
		<th>Fulfillment no. </th>
		<th>Fulfillment date</th>
		<th>Sales order no.</th>
		<th>Delivery Mode</th>
		<th>Delivery Address</th>
		<th>Tracking no.</th>
		<th>Received</th>
		<th>Actions</th>		
	</thead>
	<tbody>
	<?php 
	foreach ($fulfillments as $currentOrder){
	?>
		<tr>
			<td><?php echo $currentOrder->tranid; ?></td>
			<td><?php echo $currentOrder->trandate; ?></td>
			<td><?php echo $currentOrder->createdfrom; ?></td>
			<td><?php echo $currentOrder->deliverymode->name; ?></td>
			<td><?php echo nl2br($currentOrder->shipaddress); ?></td>
			<td><?php echo $currentOrder->custbodytrackingnumber; ?></td>
			<td><?php echo $currentOrder->custbody_website_received; ?></td>
			<td>
				<?php if ($currentOrder->custbody_website_received == 'No'){ ?>
					 <a class="a-markrecv" href="" data-internalid="<?php echo $currentOrder->internalid; ?>">Mark Received</a>
				<?php }?>	
			</td>
		</tr>
	<?php 
	}
	?>
	</tbody>
</table>
<form id="custom-form" method="post" action="">
	<input name="fulfillment_internalid" id="fulfillment_internalid" type="hidden" value="" />
</form>
<div class="">
	<a href="portal.php" class="btn btn-wcs-default btn-lg" >Back</a>
</div>
<?php 
	include("templates/footer_tag.php");
?>
