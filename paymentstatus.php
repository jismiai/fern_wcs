<?php
/*** 
 * Function : Read the outstanding invoices & customer balances. Then output them
 * Read : Suitelet
 * Output : PHP
 */
require_once 'controllers/log_control.php';

/* This section of code reads JSON output by 
 * SuiteLet PaymentStatus - Get Invoice 
 * */
$url = 'https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=238&deploy=1&compid=3716988&h=fb819db01f072abfa76e';
$postContent = array("custid" => $_SESSION["customerID"]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //curl error SSL certificate problem, verify that the CA cert is OK

$response = curl_exec($ch);
//JSON response is decoded and saved in $invoices
$invoices = json_decode($response);

/* This section of code reads JSON output by
 * SuiteLet PaymentStatus - Get Invoice
 * 
 * The same curl is reused
* */
$url = 'https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=239&deploy=1&compid=3716988&h=be3075b42449b79650c6';
curl_setopt($ch,CURLOPT_URL, $url);
$response = curl_exec($ch);
//JSON response is decoded and saved in $customerBalances
$customerBalances = json_decode($response);

include("templates/head_tag.php");
?>
<h3>Payment Status</h3>
<table class="table table-hover">
	<thead>
		<th>Invoice date</th>
		<th>Invoice no.</th>
		<th>Sales order no.</th>
		<th>Sales amount</th>
		<th>Amount paid </th>
		<th>Balance</th>
		<th>Outstanding days</th>
	</thead>
	<tbody>
	<?php 
	foreach ($invoices as $currentInvoice){
	?>
	<tr>
		<td><?php echo $currentInvoice->trandate; ?></td>
		<td><?php echo $currentInvoice->tranid; ?></td>
		<td><?php echo $currentInvoice->createdfrom->name; ?></td>
		<td><?php echo $currentInvoice->currency.number_format($currentInvoice->total,2); ?></td>
		<td><?php echo $currentInvoice->currency.number_format($currentInvoice->amountpaid,2); ?></td>
		<td><?php echo $currentInvoice->currency.number_format($currentInvoice->amountremaining,2); ?></td>
		<td><?php echo $currentInvoice->age; ?></td>
	</tr>
	<?php 
	}
	?>
	</tbody>
</table>

<h3>Financial Balance Record</h3>
<table class="table table-hover">
	<thead>
	</thead>
	<tbody>
		<tr>
			<th>Outstanding</th>
			<td><?php echo $customerBalances->displaysymbol. number_format($customerBalances->balance,2); ?></td>
		</tr>
		<tr>
			<th>Deposit</th>
			<td><?php echo $customerBalances->displaysymbol. number_format($customerBalances->depositbalance,2); ?></td>
		</tr>
		<tr>
			<th>Overdue</th>
			<td><?php echo $customerBalances->displaysymbol. number_format($customerBalances->overduebalance,2); ?></td>
		</tr>
	</tbody>
</table>
<div class="">
<a href="portal.php" class="btn btn-wcs-default btn-lg" >Back</a>
</div>
<?php 
include("templates/footer_tag.php");
?>