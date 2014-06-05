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
$url = 'https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=242&deploy=1&compid=3716988&h=6836869292fff21643f3';
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
$orders = json_decode($response);
/*echo "<pre>";
var_dump($orders);
echo "</pre>";
*/
//update display setting if necessary

//display the list
$custom_head = '		
<script>
$(document).ready(function(){
	$(".a-orderdetail").click(function(){	
		var so_id = $(this).attr("data-internalid");
		$("#so_internalid").val(so_id);
		$("#custom-form").attr("action","orderdetail.php");
		$("#custom-form").submit();
		return false;
	});
	$(".a-markrecv").click(function(){	
		var so_id = $(this).attr("data-internalid");
		$("#so_internalid").val(so_id);
		$("#custom-form").attr("action","controllers/receiveorder.php");
		$("#custom-form").submit();
		return false;
	});
});
</script>';
include("templates/head_tag.php");

//display success msg for order receipt
if (isset($GET["success"]) && $GET["success"]==true){?>
	<div class="col-sm-12">
	<div class="panel panel-success">
	<div class="panel-heading">
	<h2 class="panel-title">Success</h2>
	</div>
	<div class="panel-body">
				<p>Order received.</p>
			</div>
		</div>
	</div>
<?php } ?>

<h3>Orders</h3>
<table class="table table-hover">
	<thead>
		<th>Sales order date</th>
		<th>Sales order no.</th>
		<th>Delivery Mode</th>
		<th>Delivery Address</th>
		<th>Tracking no.</th>
		<th>Status</th>
		<th>Actions</th>		
	</thead>
	<tbody>
	<?php 
	foreach ($orders as $currentOrder){
	?>
		<tr>
			<td><?php echo $currentOrder->trandate; ?></td>
			<td><?php echo $currentOrder->tranid; ?></td>
			<td><?php echo $currentOrder->deliverymode->name; ?></td>
			<td><?php echo nl2br($currentOrder->shipaddress); ?></td>
			<td><?php echo $currentOrder->trandate; ?></td>
			<td><?php echo $currentOrder->status; ?></td>
			<td>
				<a class="a-orderdetail" href="" data-internalid="<?php echo $currentOrder->internalid; ?>">View Detail</a>
				<?php
					//show the link only if the order needs to be received.
					if ($currentOrder->status =="Delivery in progress" || 1==1){ ?> 
					| <a class="a-markrecv" href="" data-internalid="<?php echo $currentOrder->internalid; ?>">Mark Received</a></td>
				<?php }?>
		</tr>
	<?php 
	}
	?>
	</tbody>
</table>
<form id="custom-form" method="post" action="">
	<input name="so_internalid" id="so_internalid" type="hidden" value="" />
</form>
<div class="">
	<a href="portal.php" class="btn btn-default btn-lg" >Back</a>
</div>
<?php 
	include("templates/footer_tag.php");
?>
