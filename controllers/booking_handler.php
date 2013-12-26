<?php
require_once 'log_control.php';

/*determine add/edit mode to generate add/update request*/

/*read form-input */

//store post variable in a separate to manipulate data.
$post = array();
foreach ($_POST as $key => $value) {
	$post[$key] = $value;
}
//process purposes and produce a string separated by comma.
$purpose=$_POST["purpose_purchase"].", ".$_POST["purpose_alter"].", ".$_POST["purpose_other"];
$purpose=trim($purpose,", ");
/*if(strlen($post["purpose_other"]) >=1){
	$post['purpose'] .= $post["purpose_other"];
}
if(strlen($post["purpose_alter"]) >=1){
	if (strlen($post['purpose']) > 0)
		$post['purpose'] = ", ".$post['purpose'];
	$post['purpose'] .= $post["purpose_alter"].$post['purpose'];
}
if(strlen($post["purpose_purchase"]) >=1){
	if (strlen($post['purpose']) > 0)
		$post['purpose'] = ", ".$post['purpose'];
	$post['purpose'] .= $post["purpose_purchase"].$post['purpose'];
}*/
//process purchase item multi-selection list
$purchase_item = "";
if (isset($_POST['purpose_purchase']) && strlen($_POST['purpose_purchase'])){
	foreach($_POST['purchase_item'] as $item){
		$purchase_item .= $item.", ";
		echo $item. "added <br />";
	}
	$purchase_item = trim($purchase_item,", ");
}
$alter_item = "";
if (isset($_POST['purpose_alter']) && strlen($_POST['purpose_alter'])){
	foreach($_POST['alter_item'] as $item){
		$alter_item .= $item.", ";
	}
	$alter_item = trim($alter_item, ", ");
}
/*perform search again to determine whether this slot is still available */

$custom_head="";
$custom_title="- Your Booking";
include("../templates/head_tag.php");
?>

<?php 
echo "date =". $_POST["appoint_date"]."<br />";
echo "time =". $_POST["appoint_time"]."<br />";
echo "purpose =".$purpose."<br />";
echo "purchase item = ".$purchase_item."<br />";
echo "alter item = ".$alter_item."<br />";
echo "otherdetails = ".$_POST["otherdetails"]."<br />";
echo "ch_measure= ".$_POST["ch_measure"]."<br />";
echo "num_companion = ".$_POST["num_companion"]."<br />";
echo "name_companion = ".$_POST["name_companion"]."<br />";
echo "refer = ".$_POST["refer"]."<br />";
echo "message".$_POST["message"]."<br />";

echo "".$_POST[""]."<br />";
?>

<?php 
require_once '../templates/footer_tag.php';
?>