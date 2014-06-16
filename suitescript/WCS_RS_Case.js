/** Developed by David@Fern for William Cheng & Son during 2014
 *  Functions called by website to retrieve information from Netsuite.
 */

function getCustomerInfo(datain){
	//getRequestID - custid
	var output ='';
	if (!datain.custid){
		output.isSuccess = false;
		output.err_code = 'MISSING_ARGUMENT';
		nlapiLogExecution('ERROR','MISSING_ARGUMENT','No argument custid');
		return output;
	}
	var recCustomer = nlapiLoadRecord('customer',datain.custid);
	var Customer = new Object();
	Customer.entityid = recCustomer.getFieldValue('entityid');
	Customer.name = recCustomer.getFieldValue('firstname') + " " + recCustomer.getFieldValue('lastname');
	Customer.email = recCustomer.getFieldValue('email');
	Customer.phone = recCustomer.getFieldValue('phone');
	if (Customer.phone === null){
		Customer.phone = '';
	}
	Customer.isSuccess = true;
	nlapiLogExecution('AUDIT','Complete Run','Data fetched by customer id: '+ datain.custid);
	return Customer;
}

function createCase(datain){
	var recCase = nlapiCreateRecord('supportcase');
	if (!datain.custid || !datain.case_subtype){
		return;
	}
	recCase.setFieldValue('company',datain.custid);
	recCase.setFieldValue('email',datain.email);
	recCase.setFieldValue('phone',datain.phone);
	recCase.setFieldValue('category',datain.case_type);
	recCase.setFieldValue('custevent_case_submenu',datain.case_subtype);
	recCase.setFieldValue('title',datain.title);
	switch(datain.case_subtype){ //before submit codes
		case "13":
			recCase.setFieldValue('customform','53'); //form for place order
			break;
		default:
			nlapiLogExecution('DEBUG','casesubtype',datain.case_subtype);
			break;
	}
	/*var recid = nlapiSubmitRecord(recCase, true);
	switch(datain.case_subtype){ //after submit codes, required for sublists..
		case "13":
			var recCase = nlapiLoadRecord('supportcase',recid);
			for (var i=0;i<datain.order_req.length;i++){
				recCase.selectNewLineItem('recmachcustrecord_parent_case_no');
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_producttype',datain.order_req[i].custrecord_orderreq_producttype);
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_quantity',datain.order_req[i].custrecord_orderreq_quantity);
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_fabricbrand',datain.order_req[i].custrecord_orderreq_fabricbrand);
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_fabriccolor',datain.order_req[i].custrecord_orderreq_fabriccolor);
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_fabric_pattern',datain.order_req[i].custrecord_orderreq_fabric_pattern);
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_fabricmaterial',datain.order_req[i].custrecord_orderreq_fabricmaterial);
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_otherreq',datain.order_req[i].custrecord_orderreq_otherreq);
				recCase.setCurrentLineItemValue('recmachcustrecord_parent_case_no','custrecord_orderreq_expdate',datain.order_req[i].custrecord_orderreq_expdate);
				recCase.commitLineItem('recmachcustrecord_parent_case_no');
			}
			break;
	}*/
	//var recid = nlapiSubmitRecord(recCase, true);
	//return recCase.getFieldValue('casenumber');
	return datain;
}