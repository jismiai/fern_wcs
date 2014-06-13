<?php
class netsuiteCase {
	private $caseType = array('2' => 'Order','4'=>'Alteration','3'=>'Complaint','1'=>'General Enquiry');
	private $subType = array(
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
	private $productType = array(
			'1' => 'Shirt',
			'2' => 'Suit with Trousers/Pant',
			'3' => 'Vest',
			'4' => 'Topcoat',
			'5' => 'Suit with Skirt',
			'6' => 'Jacket',
			'7' => 'Trousers'
	);
	private $fabricBrand = array('Zegna','V.B');
	private $fabricColor = array('White','Black','Grey','Blue','Brown','Red','Yellow','Khaki','Green','Others');
	private $fabricPattern = array('Plain','Stripes','Check','Others');
	private $fabricMaterial = array('Cotton','Wool','Others');
	
	function caseTypeHTML($typeId){
		//output a <select> element listing all case types
		echo '<select name="case_type" class="form-control">';
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
		//output a <select> element listing all case sub-types
		echo '<select name="case_subtype" class="form-control">';
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
		//output a <select> element listing all product types
		$dataout ="";
		$dataout .= '<select name="product_type[]" class="form-control">';
		foreach ($this->productType as $key => $value){
			if ($key ==$typeId){
				$dataout .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			} else {
				$dataout .= '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		$dataout .= '</select>';
		return $dataout;
	}
	function fabricBrandHTML(){
		//output a <select> element listing all product types
		$dataout ="";
		$dataout = "";
		$dataout .=  '<select name="fabric_brand[]" class="form-control">';
		$dataout .=  '<option value="">Please select a brand</option>';
		foreach ($this->fabricBrand as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	function fabricColorHTML(){
		//output a <select> element listing all product types
		$dataout ="";
		$dataout .=  '<select name="fabric_color[]" class="form-control">';
		$dataout .=  '<option value="">Please select a color</option>';
		foreach ($this->fabricColor as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	function fabricPatternHTML(){
		//output a <select> element listing all product types
		$dataout ="";
		$dataout .=  '<select name="fabric_pattern[]" class="form-control">';
		$dataout .=  '<option value="">Please select a pattern</option>';
		foreach ($this->fabricPattern as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	function fabricMaterialHTML(){
		//output a <select> element listing all product types
		$dataout ="";
		$dataout .=  '<select name="fabric_material[]" class="form-control">';
		$dataout .=  '<option value="">Please select a material</option>';
		foreach ($this->fabricMaterial as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	/*methods to output addition lines for specific forms */
	private $nextLine = 1;
	function orderReqLineStr(){
		if ($this->nextLine > 1) $style='style="display:none"';
		$dataout = trim(preg_replace('/\s\s+/', ' ', '<div '.$style.' class="order_req_wrapper">
			<legend>Product '.$this->nextLine.' detail</legend>
			<div class="form-group">
				<label class="col-sm-4 control-label">Product type:</label>
				<div class="col-sm-8">
					'.$this->productTypeHTML('').'
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Quantity:</label>
				<div class="col-sm-8">
					<input type="text" name="quantity[]" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric brand:</label>
				<div class="col-sm-8">
					'.$this->fabricBrandHTML().'
			</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric color:</label>
				<div class="col-sm-8">
					'.$this->fabricColorHTML().'
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric pattern:</label>
				<div class="col-sm-8">
					'.$this->fabricPatternHTML().'
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric material:</label>
				<div class="col-sm-8">
					'.$this->fabricMaterialHTML().'
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Other order requirements:</label>
				<div class="col-sm-8">
					<textarea rows="3" name="other[]" class="form-control"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Expected received date:</label>
				<div class="col-sm-8">
					<input type="date" name="recv_date[]" class="form-control" />
				</div>
			</div>
		</div>
		'));
		$this->nextLine++;
		return $dataout;
	}
}


?>