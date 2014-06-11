<?php
class netsuiteCase {
	public $caseType = array('2' => 'Order','4'=>'Alteration','3'=>'Complaint','1'=>'General Enquiry');
	public $subType = array(
			'1' => array('parent' => '2', 'value' => 'Request for swatch and catalogue'),
			'2' => array('parent' => '2', 'value' => 'Product Pricing'),
			'3' => array('parent' => '2', 'value' => 'Fabric and product styles'),
			'4' => array('parent' => '2', 'value' => 'Order progress'),
			'5' => array('parent' => '4', 'value' => 'Pricing'),
			'6' => array('parent' => '4', 'value' => 'Timing'),
			'7' => array('parent' => '4', 'value' => 'Policies and procedures'),
			'8' => array('parent' => '4', 'value' => 'Alteration progress'),
			'9' => array('parent' => '3', 'value' => 'Remake'),
			'10' => array('parent' => '3', 'value' => 'Local Treatment'),
			'11' => array('parent' => '1', 'value' => 'Shop appointment'),
			//'12' => array('parent' => '1', 'value' => 'Others'),
			'13' => array('parent' => '2', 'value' => 'Place Order'),
			'14' => array('parent' => '2', 'value' => 'Order progress'),
			'15' => array('parent' => '4', 'value' => 'Alteration request'),
			'16' => array('parent' => '3', 'value' => 'Return and Refund'),
			'17' => array('parent' => '1', 'value' => 'Trip schedule'),
			'18' => array('parent' => '1', 'value' => 'Others'),
			'19' => array('parent' => '3', 'value' => 'Alteration')
			);
	public $productType = array(
			'1' => 'Shirt',
			'2' => 'Suit with Trousers/Pant',
			'3' => 'Vest',
			'4' => 'Topcoat',
			'5' => 'Suit with Skirt',
			'6' => 'Jacket',
			'7' => 'Trousers'
	);
	public $fabricBrand = array();
	public $fabricColor = array('White','Black','Grey','Blue','Brown','Red','Yellow','Khaki','Green','Others');
	public $fabricPattern = array('Plain','Stripes','Check','Others');
	public $fabricMaterial = array('Cotton','Wool','Others');

	function caseTypeHTML($typeId){
		echo '<select class="form-control">';
		foreach ($this->caseType as $key => $value){
			if ($key ==$typeId){
				echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			} else {
				echo '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		echo '</select>';
	}
	function subTypeHTML($parentId,$typeId){
		echo '<select class="form-control">';
		foreach ($this->subType as $key => $value){
			if ($value['parent'] == $parentId || empty($parentId)){
				if ($key ==$typeId){
					echo '<option selected="selected" value="'.$key.'">'.$value["value"].'</option>';
				} else {
					echo '<option value="'.$key.'">'.$value["value"].'</option>';
				}
			}
		}
		echo '</select>';
	}
	function productTypeHTML($typeId){
		echo '<select class="form-control">';
		foreach ($this->productType as $key => $value){
			if ($key ==$typeId){
				echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			} else {
				echo '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		echo '</select>';
	}
}


?>