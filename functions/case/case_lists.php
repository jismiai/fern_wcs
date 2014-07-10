<?php
class netsuiteCase {
	// this class is used as maintain list information as well as output required html in forms.
	private $caseType = array('2' => 'Order','4'=>'Alteration','3'=>'Complaint','1'=>'General Enquiry');
	private $subType = array(
			'1' => array('parent' => '2', 'value' => 'Request for swatch and catalogue'),
			'2' => array('parent' => '2', 'value' => 'Product Pricing'),
			'3' => array('parent' => '2', 'value' => 'Fabric and product styles'),
			//'4' => array('parent' => '2', 'value' => 'Order progress'),
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
			'19' => array('parent' => '3', 'value' => 'Alteration'),
			'21' => array('parent' => '3', 'value' => 'Lodge a complaint'),
			'22' => array('parent' => '1', 'value' => 'Change Measurement')
			);
	public $caseTypeId;
	public $subTypeId;
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
	private $complaintType = array(
			'1' => 'Product quality',
			'2' => 'Sales services',
			'3' => 'Delivery',
			'4' => 'Others'
	);
	
	function setCaseType($typeId){
		$this->caseTypeId = $typeId;
	}
	function caseTypeHTML($typeId){
		//output a <select> element listing all case types
		echo '<select name="case_type" id="case_type" class="form-control" readonly>';
		foreach ($this->caseType as $key => $value){
			if ($key ==$typeId){
				echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			} else {
				echo '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		echo '</select>';
	}
	function setSubType($typeId){
		$this->subTypeId = ($typeId);
	}
	function subTypeHTML($parentId,$typeId){
		//output a <select> element listing all case sub-types
		echo '<select name="case_subtype" id="case_subtype" class="form-control" readonly>';
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
		$multiple = "";
		$array = "";
		switch ($this->subTypeId){
			case "1":
				$multiple = "multiple";
				break;
			case "13":
			case "21":
			case "15":
				$array="[]";
				break;
		}
		
		$dataout ="";
		$dataout .= '<select name="product_type'.$array.'" class="form-control" '.$multple.'>';
		$dataout .= '<option value=""> -- Please select --</option>';
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
		$multiple = "";
		$array = "";
		switch ($this->subTypeId){
			case "1":
				break;
			case "13":
				$array="[]";
				break;
		}
		
		$dataout = "";
		$dataout .=  '<select name="fabric_brand'.$array.'" class="form-control" '.$multiple.'>';
		$dataout .=  '<option value="">--Please select a brand--</option>';
		foreach ($this->fabricBrand as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	function fabricColorHTML(){
		//output a <select> element listing all product types
		$multiple = "";
		$array = "";
		switch ($this->subTypeId){
			case "1":
				$multiple = "multiple";
				$array="[]";
				break;
			case "13":
				$array="[]";
				break;
		}
		
		$dataout ="";
		$dataout .=  '<select name="fabric_color'.$array.'" class="form-control fabric_color" '.$multiple.'>';
		$dataout .=  '<option value="">--Please select a color--</option>';
		foreach ($this->fabricColor as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	function fabricPatternHTML(){
		//output a <select> element listing all product types
		$multiple = "";
		$array = "";
		switch ($this->subTypeId){
			case "1":
				$multiple = "multiple";
				$array="[]";
				break;
			case "13":
				$array="[]";
				break;
		}
		
		$dataout ="";
		$dataout .=  '<select name="fabric_pattern'.$array.'" class="form-control" '.$multiple.'>';
		$dataout .=  '<option value="">--Please select a pattern--</option>';
		foreach ($this->fabricPattern as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	function fabricMaterialHTML(){
		//output a <select> element listing all product types
		$multiple = "";
		$array = "";
		switch ($this->subTypeId){
			case "13":
				$array="[]";
				break;
		}
		
		$dataout ="";
		$dataout .=  '<select name="fabric_material'.$array.'" class="form-control fabric_material" '.$multiple.'>';
		$dataout .=  '<option value="">--Please select a material--</option>';
		foreach ($this->fabricMaterial as $value){
			$dataout .=  '<option value="'.$value.'">'.$value.'</option>';
		}
		$dataout .=  '</select>';
		return $dataout;
	}
	
	function complaintTypeHTML(){
		//output a <select> element listing all product types
	
		$dataout ="";
		$dataout .= '<select name="complaint_type" class="form-control">';
		foreach ($this->complaintType as $key => $value){
			if ($key ==$typeId){
				$dataout .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			} else {
				$dataout .= '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		$dataout .= '</select>';
		return $dataout;
	}
	
	/*methods to output addition lines for specific forms */
	private $nextLine = 1;
	function orderReqLineStr(){
		if ($this->nextLine > 1) {
			$style='style="display:none"';
			$lineno = "'+lineno+'";
		} else {
			$lineno = "1";
		}
		$dataout = trim(preg_replace('/\s\s+/', ' ', '<div '.$style.' class="order_req_wrapper">
			<legend>Product '.$lineno.' detail</legend>
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
				<label class="col-sm-4 control-label">Fabric number:</label>
				<div class="col-sm-8">
					<input type="text" name="fabric_number[]" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric brand:</label>
				<div class="col-sm-4">
					'.$this->fabricBrandHTML().'
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric color:</label>
				<div class="col-sm-4">
					'.$this->fabricColorHTML().'
				</div>
				<div class="col-sm-4">
					<input style="display:none" type="text" name="fabric_color_other[]" class="form-control fabric_color_other" placeholder="please specify here"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric pattern:</label>
				<div class="col-sm-4">
					'.$this->fabricPatternHTML().'
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Fabric material:</label>
				<div class="col-sm-4">
					'.$this->fabricMaterialHTML().'
				</div>
				<div class="col-sm-4">
					<input style="display:none" type="text" name="fabric_material_other[]" class="form-control fabric_material_other" placeholder="please specify here" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Other order requirements:</label>
				<div class="col-sm-8">
					<textarea rows="3" name="other[]" class="form-control"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Expected received date:<br /></label>
				<div class="col-sm-8">
					<input type="text" name="recv_date[]" class="form-control recv_date" placeholder="yyyy/mm/dd" />
				</div>
			</div>
		</div>
		'));
		$this->nextLine++;
		return $dataout;
	}
	
	function complaintLineStr(){
		if ($this->subTypeId == "21"){ //complaint
			$legend_title = "Complaint item detail";
		} elseif ($this->subTypeId == "15") { //alteration request
			$legend_title = "Alteration item detail";
htmlblock;
		}
		
		if ($this->nextLine > 1) {
			$style='style="display:none"';
			$lineno = "'+lineno+'";
		} else {
			$lineno = "1";
		}
		$str = <<<codeblock
		<div {$style} class="complaint_item_wrapper">
			<legend>{$legend_title} - Item {$lineno}</legend>
			<div class="form-group">
				<label class="col-sm-4 control-label">Product type:</label>
				<div class="col-sm-8">
					{$this->productTypeHTML('')}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Quantity:</label>
				<div class="col-sm-8">
					<input type="text" name="quantity[]" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Problematic part</label>
				<div class="col-sm-8">
					<input type="text" name="problem_part[]" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Measurement Change</label>
				<div class="col-sm-8">
					<input type="text" name="complaint_mea_change[]" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Other Description</label>
				<div class="col-sm-8">
					<textarea rows="3" name="other[]" class="form-control"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Sales/Alt order no.</label>
				<div class="col-sm-8">
					<input type="text" name="complaint_so[]" class="form-control" />
				</div>
			</div>
		</div>
codeblock;
		$dataout = trim(preg_replace('/\s\s+/', ' ', $str));
		$this->nextLine++;
		return $dataout;
	}
		
	function caseFormTypeHTML(){
		switch ($this->subTypeId){
			case "1":
				$dataout = <<<codeblock
				<fieldset>
					<legend>Request for swatch</legend>
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Product Type:</label>
						<div class="col-sm-8">
							{$this->productTypeHTML('')}
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Fabric brand:</label>
						<div class="col-sm-4">
							{$this->fabricBrandHTML()}
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Fabric color:</label>
						<div class="col-sm-4">
							{$this->fabricColorHTML()}
						</div>
						<div class="col-sm-4">
							<input style="display:none" type="text" name="fabric_color_other" class="form-control fabric_color_other" placeholder="please specify here"/>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Fabric pattern:</label>
						<div class="col-sm-4">
							{$this->fabricPatternHTML()}
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Fabric materials:</label>
						<div class="col-sm-4">
							{$this->fabricMaterialHTML()}
						</div>
						<div class="col-sm-4">
							<input style="display:none" type="text" name="fabric_material_other" class="form-control fabric_material_other" placeholder="please specify here" />
						</div>
					</div>
					<div class="form-group">
						<label for="other" class="col-sm-4 control-label">Other requirements:</label>
						<div class="col-sm-8">
							<textarea rows="3" name="other" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="swatch_type" class="col-sm-4 control-label">Swatch type:</label>
						<div class="col-sm-8">
							<label class="radio-inline">
								<input type="radio" name="swatch_type" id="swatch_type_image" class="radio_box" value="Image" >Image
							</label>
							<label class="radio-inline">
								<input type="radio" name="swatch_type" id="swatch_type_fabric" class="radio_box" value="Fabric" >Fabric
							</label>
						</div>
					</div>
					</fieldset>
					<fieldset>
					<legend>Request for catalogue</legend>
					<div class="form-group">
						<label for="catalogue_name" class="col-sm-4 control-label">Cataglogue:</label>
						<div class="col-sm-8">
							<select name="catalogue_name" class="form-control">
								<option value="">Please select a catalogue</option>
								<option>Suit catalogue</option>
								<option>Shirt catalogue</option>
							</select>
						</div>
					</div>
					
codeblock;
				break;
			case "13":
				$dataout = <<<codeblock
				<fieldset>
					<div id="order_req_list">
						{$this->orderReqLineStr()}
					</div>
					<div class="form-group" style="">
						<div class="col-sm-offset-4 col-sm-4" >
							<button class="btn" id="add_order_row">Add more</button>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>Attachments</legend>
					<div class="form-group">
						<label for="file" class="col-sm-4 control-label">Order form:</label>
						<div class="col-sm-8">
							<input type="file" name="file" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label for="photo" class="col-sm-4 control-label">Upload Photo:</label>
						<div class="col-sm-8">
							<input type="file" name="photo" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label for="photo" class="col-sm-4 control-label">Upload Photo 2:</label>
						<div class="col-sm-8">
							<input type="file" name="photo2" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label for="photo" class="col-sm-4 control-label">Upload Photo 3:</label>
						<div class="col-sm-8">
							<input type="file" name="photo3" class="form-control" />
						</div>
					</div>
				</fieldset>
codeblock;
				break;
			case "14":
				$dataout = <<<codeblock
				<fieldset>
				<legend>Order/Alteration Progress</legend>
				<div class="form-group">
					<label for="other" class="col-sm-4 control-label">Order no.</label>
					<div class="col-sm-8">
						<input type="text" name="order_no" class="form-control" />
					</div>
					</div>
				<div class="form-group">
					<label for="other" class="col-sm-4 control-label">Other message:</label>
					<div class="col-sm-8">
						<textarea rows="3" name="other" class="form-control"></textarea>
					</div>
				</div>
			</fieldset>
codeblock;
				break;
			case "15":
				$dataout = <<<codeblock
			<fieldset>
				<div id="complaint_item_list">
					{$this->complaintLineStr()}
				</div>
				<div class="form-group" style="">
					<div class="col-sm-offset-4 col-sm-4" >
						<button class="btn" id="add_complaint_row">Add more</button>
					</div>
				</div>
			</fieldset>
codeblock;
				break;
			case "18":
				$dataout = <<<codeblock
				<fieldset>
				<legend>General Inquiry</legend>
				<div class="form-group">
					<label for="other" class="col-sm-4 control-label">Message:</label>
					<div class="col-sm-8">
						<textarea rows="5" name="case_detail" class="form-control"></textarea>
					</div>
				</div>
			</fieldset>
codeblock;
				break;
			case "21": //complaint
				$dataout = <<<codeblock
<fieldset>
				<legend>Complaint</legend>
				<div class="form-group">
					<label for="complaint_type" class="col-sm-4 control-label">Complaint type:</label>
					<div class="col-sm-8">
						{$this->complaintTypeHTML()}
					</div>
				</div>
				<div class="form-group">
					<label for="complaint_detail" class="col-sm-4 control-label">Complaint details:</label>
					<div class="col-sm-8">
						<textarea name="complaint_detail" rows="3" class="form-control" placeholder="Enter detail here.."></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Request:</label>
					<div class="col-sm-8">
						<select class="form-control" name="complaint_request">
							<option value="1">Alteration</option>
							<option value="2">Remake</option>
							<option value="3">Return</option>
							<option value="4">Refund</option>
							<option value="5">Discount</option>
							<option value="8">Others (Please specify on detail)</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="photo" class="col-sm-4 control-label">Upload Photo:</label>
					<div class="col-sm-8">
						<input type="file" name="photo" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label for="photo" class="col-sm-4 control-label">Upload Photo 2:</label>
					<div class="col-sm-8">
						<input type="file" name="photo2" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label for="photo" class="col-sm-4 control-label">Upload Photo 3:</label>
					<div class="col-sm-8">
						<input type="file" name="photo3" class="form-control" />
					</div>
				</div>	
			</fieldset>
			
			<fieldset>
				<div id="complaint_item_list">
					{$this->complaintLineStr()}
				</div>
				<div class="form-group" style="">
					<div class="col-sm-offset-4 col-sm-4" >
						<button class="btn" id="add_complaint_row">Add more</button>
					</div>
				</div>
			</fieldset>
codeblock;
				break;
			case "22":
				$dataout= <<<codeblock
				<fieldset>
					<legend>Change Measurement</legend>
					<div class="form-group">
						<label for="weight_increase" class="col-sm-4 control-label">Weight Change</label>
						<div class="col-sm-2">
							<select name="weight_increase" class="form-control">
								<option value="">Unchanged</option>
								<option value="1">Increase</option>
								<option value="2">Decrease</option>
							</select>
						</div>					
							<div class="col-sm-2">
								<input type="text" class="form-control" name="weight_change_kg" value="" placeholder="kg" />
							</div>						
							<p for="weight_change_kg" class="col-sm-1 form-control-static">KG</p>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Size Change:</label>
						<div class="col-sm-8">
							<div class="col-sm-11">
								<label class="col-sm-4 checkbox-inline">
									<input type="checkbox" name="change_neck" value="T" />Neck
								</label>
								<a href="" class="form-control-static col-sm-1 minus" data-target="neck_inch">-</a> 
								<div class="col-sm-3">
									<input type="text" name="neck_inch" id="neck_inch" class="form-control" data-decimal="0.0" value="0" readonly/>
								</div>
								<a href="" class="form-control-static col-sm-1 plus" data-target="neck_inch">+</a>
								<p class="form-control-static col-sm-1">Inch</p>
							</div>
							<div class="col-sm-11">
								<label class="col-sm-4 checkbox-inline">
									<input type="checkbox" name="change_chest" value="T" />Chest
								</label>
								<a href="" class="form-control-static col-sm-1 minus" data-target="chest_inch">-</a> 
								<div class="col-sm-3">
									<input type="text" name="chest_inch" id="chest_inch" class="form-control" data-decimal="0.0" value="0" readonly/>
								</div>
								<a href="" class="form-control-static col-sm-1 plus" data-target="chest_inch">+</a>
								<p class="form-control-static col-sm-1">Inch</p>
							</div>
							<div class="col-sm-11">
								<label class="col-sm-4 checkbox-inline">
									<input type="checkbox" name="change_waist" value="T" />Waist
								</label>
								<a href="" class="form-control-static col-sm-1 minus" data-target="waist_inch">-</a> 
								<div class="col-sm-3">
									<input type="text" name="waist_inch" id="waist_inch" class="form-control" data-decimal="0.0" value="0" readonly/>
								</div>
								<a href="" class="form-control-static col-sm-1 plus" data-target="waist_inch">+</a>
								<p class="form-control-static col-sm-1">Inch</p>
							</div>
							<div class="col-sm-11">
								<label class="col-sm-4 checkbox-inline">
									<input type="checkbox" name="change_hip" value="T" />Hip
								</label>
								<a href="" class="form-control-static col-sm-1 minus" data-target="hip_inch">-</a> 
								<div class="col-sm-3">
									<input type="text" name="hip_inch" id="hip_inch" class="form-control" data-decimal="0.0" value="0" readonly/>
								</div>
								<a href="" class="form-control-static col-sm-1 plus" data-target="hip_inch">+</a>
								<p class="form-control-static col-sm-1">Inch</p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="other" class="col-sm-4 control-label">Other changes:</label>
						<div class="col-sm-8">
							<textarea rows="3" name="other" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="photo" class="col-sm-4 control-label">Upload Photo:</label>
						<div class="col-sm-8">
							<input type="file" name="photo" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label for="photo" class="col-sm-4 control-label">Upload Photo 2:</label>
						<div class="col-sm-8">
							<input type="file" name="photo2" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label for="photo" class="col-sm-4 control-label">Upload Photo 3:</label>
						<div class="col-sm-8">
							<input type="file" name="photo3" class="form-control" />
						</div>
					</div>	
					</fieldset>
				
codeblock;
				break;
		}
		return $dataout;
	}
	function caseJavascript(){
		switch ($this->subTypeId){
			case "13":
			$dataout = <<<codeblock
			<script>
			$(document).ready(function(){
				var lineno = 2;
				$("#add_order_row").click(function(){
					var tblrow = '{$this->orderReqLineStr()}';
					$("#order_req_list").append(tblrow);
					$(".order_req_wrapper:last").slideDown('slow');
					lineno++;
					return false;
				});
				$(document).on("change",".fabric_color",function(){
					if ($(this).val() == "Others"){
						$(this).parent().next().children(".fabric_color_other").show();
					} else {
						$(this).parent().next().children(".fabric_color_other").hide();	
					}
				});
				$(document).on("change",".fabric_material",function(){
					if ($(this).val() == "Others"){
						$(this).parent().next().children(".fabric_material_other").show();
					} else {
						$(this).parent().next().children(".fabric_material_other").hide();	
					}
				});
			});
			</script>
codeblock;
			break;
			case "1":
				$dataout = <<<codeblock
				<script>
			$(document).ready(function(){
				$(document).on("change",".fabric_color",function(){
					if ($(this).val().indexOf("Others") != -1){
						$(this).parent().next().children(".fabric_color_other").show();
					} else {
						$(this).parent().next().children(".fabric_color_other").hide();	
					}
				});
				$(document).on("change",".fabric_material",function(){
					if ($(this).val() == "Others"){
						$(this).parent().next().children(".fabric_material_other").show();
					} else {
						$(this).parent().next().children(".fabric_material_other").hide();	
					}
				});
			});
			</script>
codeblock;
			break;
			case "15":
			case "21":
				//$java_str = preg_replace("/'/", "\&#39;", $this->complaintLineStr());
				$java_str = $this->complaintLineStr();
				$dataout = <<<codeblock
				<script>
				$(document).ready(function(){
					var lineno = 2;
					$("#add_complaint_row").click(function(){
						var tblrow = '{$java_str}';
						$("#complaint_item_list").append(tblrow);
						$(".complaint_item_wrapper:last").slideDown('slow');
						lineno++;
						return false;
					});
				});
				</script>
codeblock;
				break;
			case "22":
				$dataout = <<<codeblock
				<script>
				function dec2frac(d) {
				    var df = 1, top = 1, bot = 1;
				    var limit = 1e5; //Increase the limit to get more precision. 
				    while (df != d && limit-- > 0) {
				        if (df < d) {
				            top += 1;
				        }
				        else {
				            bot += 1;
				            top = parseInt(d * bot, 10);
				        }
				        df = top / bot;
				    }
				    //custom code for this project
				    var fracInt = 0;
				    if (d > 0) {
				        fracInt = Math.floor(top/bot);
				    }
				    if (d < 0) {
				    	fracInt = Math.ceil(top/bot);
				    }
				    if (fracInt >= 1 || fracInt <=-1){
				        var outInt = fracInt+ ' ';
				    } else if ( (top/bot) > -1 && (top/bot) < 0){
				        var outInt = '-';
				    } else if ( (top/bot) == 0) {
				        var outInt = '0';
				    } else {
						var outInt = '';
				    }
				    if ( (top%bot) == 0){
						return outInt;
				    }
				    return outInt  + Math.abs(top%bot) + '/' +bot;
				}
				$(document).ready(function(){
					$(".plus").click(function(){
						var targetBox = "#"+$(this).attr("data-target");
						var newVal = Number($(targetBox).attr("data-decimal"))+0.125;
						$(targetBox).attr("data-decimal",newVal);
						$(targetBox).val(dec2frac(newVal));
						return false;
					});
					$(".minus").click(function(){
						var targetBox = "#"+$(this).attr("data-target");
						var newVal = Number($(targetBox).attr("data-decimal"))-0.125;
						$(targetBox).attr("data-decimal",newVal);
						$(targetBox).val(dec2frac(newVal));
						return false;
					});
				});
				</script>
codeblock;
				break;
		}
		return $dataout;
	}
}


?>