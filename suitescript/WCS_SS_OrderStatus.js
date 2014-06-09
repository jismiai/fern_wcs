function getWebOrderStatus(status_internalid,received,sentdate){
	switch(status_internalid){
	//'pendingFulfillment' fullyBilled pendingApproval partiallyFulfilled pendingBilling pendingBillingPartFulfilled closed
		case 'pendingApproval':
		case 'Pending Approval':
			return 'Order in progress';
			break;
		case 'pendingFulfillment':
		case 'Pending Fulfillment':
		case 'partiallyFulfilled':
		case 'Partially Fulfilled':
		case 'pendingBillingPartFulfilled':
		case 'Pending Billing/Partially Fulfilled':
			return 'Production in progress';
			break;
		case 'pendingBilling':
		case 'Pending Billing':
		case 'fullyBilled':
		case 'Billed':
			if (received == true){
				return 'Completed';
			}
			if (received == false){
				return 'Delivery in progress';
			}
			return 'undefined';
		case 'closed':
			//empty sent date means no fulfillment is required
			if (received == true || sentdate ==''){
				return 'Completed';
			}
			if (received == false){
				return 'Delivery in progress';
			}
			return 'undefined';
			break;
		default:
			return 'undefined';
	}
}

function suitelet_getOpenSalesOrder(request, response){
	//constants for this function only
	var pickUpByCustomerID = '3';
	
	// verify if the request contains 'custid'. If not, output error
	var customer_internalId = request.getParameter('custid');
	if (!customer_internalId){
		nlapiLogExecution('ERROR',"request","not custid argument");	
		return;
	}	
	/* prepare and execute saved search */
	var salesorder_search = nlapiLoadSearch('transaction', 'customsearch_website_order_status_main');
	var filters = salesorder_search.getFilters();
	var someCriteria = new nlobjSearchFilter('entity',null, 'anyof', customer_internalId);
	salesorder_search.addFilter(someCriteria);
	salesorder_search.saveSearch();
	var salesorder_searchResult = nlapiSearchRecord('transaction', 'customsearch_website_order_status_main');
	salesorder_search.setFilters(filters);
	salesorder_search.saveSearch();
	if (salesorder_searchResult != null){
		var salesorders = new Array();
		var resultSize = Math.min(10,salesorder_searchResult.length);
		//response.write(JSON.stringify(salesorder_searchResult[0]));
		//response.write("has" + salesorder_searchResult.length+" result. result size = "+resultSize+" <br />");
		for (var i = 0; i < resultSize; i++){
			salesorders[i] = {};
			var json_data = JSON.stringify(salesorder_searchResult[i]);
			var json_salesorder = JSON.parse(json_data);
			salesorders[i].internalid = json_salesorder.id;
			salesorders[i].trandate = json_salesorder.columns.trandate;
			salesorders[i].tranid = json_salesorder.columns.tranid;
			salesorders[i].deliverymode = {};
			salesorders[i].deliverymode.name = json_salesorder.columns.custbody4.name;
			salesorders[i].deliverymode.internalid = json_salesorder.columns.custbody4.internalid;
			if (salesorders[i].deliverymode.internalid != pickUpByCustomerID){ //3 stands for pickup by customer
				salesorders[i].shipaddress = json_salesorder.columns.shipaddress;
			} else {
				salesorders[i].shipaddress = '';
			}
			salesorders[i].custbody_website_received = json_salesorder.columns.custbody_website_received;
			salesorders[i].custbody_tracking_number = json_salesorder.columns.custbody_tracking_number;
			if (json_salesorder.columns.custbody_website_sent_date && json_salesorder.columns.custbody_website_sent_date.type !== "undefined"){
				salesorders[i].custbody_website_sent_date = json_salesorder.columns.custbody_website_sent_date;
			} else {
				salesorders[i].custbody_website_sent_date = '';
			}
			salesorders[i].status = getWebOrderStatus(json_salesorder.columns.statusref.internalid,salesorders[i].custbody_website_received,salesorders[i].custbody_website_sent_date);
			//response.write(JSON.stringify(salesorders[i]) + "<br /><br />");
		}
		response.write(JSON.stringify(salesorders));
	//	nlapiLogExecution('AUDIT',"Saved Search","Executed on customer internalid: "+ customer_internalId +". "+resultSize+" results returned.");
	} else {
		//nlapiLogExecution('AUDIT',"Saved Search","Executed on customer internalid: "+ customer_internalId +". No result is found.");
	}
}
function markOrderReceived(datain){
	/** Restlet function
	 *  error codes
	 *  RCRD_DSNT_EXIST : thrown by nlapiLoadRecord if loading a non-exist record
	 *  MISSING_ARGUMENT : not enough argument from request JSON
	 */
	var output = {};
	//getRequestID - so_internalid & custid
	var customer_internalId = datain.custid;
	var so_internalId = datain.so_internalid;
	if (!customer_internalId || !so_internalId){
		output.isSuccess = false;
		output.err_code = 'MISSING_ARGUMENT';
		return output;
	}
	try { //throw error if the sales order's internalid is not valid
		var recSO = nlapiLoadRecord('salesorder',so_internalId);
	}
	catch (err){
		if (err instanceof nlobjError){	
			output.isSuccess = false;
			output.err_code = err.getCode();
			return output;
		}
	}
	//validate so_internalid matches custid
	var so_custid = recSO.getFieldValue('entity');
	if (so_custid != customer_internalId){
		output.isSuccess = false;
		output.err_code = "INVALID_CUSTOMER_ID";
		return output;
	}
	
	//update field
	recSO.setFieldValue('custbody_website_received','T');
	nlapiSubmitRecord(recSO);
	nlapiLogExecution('AUDIT',"Success","Marked received on SO internal ID:"+so_internalId);
	output.isSuccess = true;
	output.id = so_internalId;
	return output;
}

function viewOrder(datain){
	/** Restlet function
	 *  error codes
	 *  RCRD_DSNT_EXIST : thrown by nlapiLoadRecord if loading a non-exist record
	 *  MISSING_ARGUMENT : not enough argument from request JSON
	 */
	//constants for this function only
	var pickUpByCustomerID = '3';
	
	var output = {};
	//getRequestID - so_internalid & custid
	var customer_internalId = datain.custid;
	var so_internalId = datain.so_internalid;
	if (!customer_internalId || !so_internalId){
		output.isSuccess = false;
		output.err_code = 'MISSING_ARGUMENT';
		return output;
	}
	//try { //throw error if the sales order's internalid is not valid
		var recSO = nlapiLoadRecord('salesorder',so_internalId);
	/*}
	catch (err){
		if (err instanceof nlobjError){	
			output.isSuccess = false;
			output.err_code = err.getCode();
			return output;
		}
	}*/
	//validate so_internalid matches custid
	var so_custid = recSO.getFieldValue('entity');
	if (so_custid != customer_internalId){
		output.isSuccess = false;
		output.err_code = "INVALID_CUSTOMER_ID";
		return output;
	}
	/*** prepare response ***/
	var salesOrderObj = new Object();
	salesOrderObj.internalid = recSO.getFieldValue('id');
	salesOrderObj.trandate = recSO.getFieldValue('trandate');
	salesOrderObj.tranid = recSO.getFieldValue('tranid');
	salesOrderObj.deliverymode = recSO.getFieldText('custbody4');
	salesOrderObj.deliverymode_id = recSO.getFieldValue('custbody4');
	if (salesOrderObj.deliverymode_id != pickUpByCustomerID){ //3 stands for pickup by customer
		salesOrderObj.shipaddress = recSO.getFieldValue('shipaddress');
	} else {
		salesOrderObj.shipaddress = '';
	}

	salesOrderObj.custbody_website_received = recSO.getFieldValue('custbody_website_received');
	salesOrderObj.custbody_tracking_number = recSO.getFieldValue('custbody_website_tracking_number');
	salesOrderObj.custbody_tracking_number = '';
	salesOrderObj.custbody_website_sent_date = recSO.getFieldValue('custbody_website_sent_date');
	salesOrderObj.status = getWebOrderStatus(recSO.getFieldText('orderstatus'),salesOrderObj.custbody_website_received,salesOrderObj.custbody_website_sent_date);
	salesOrderObj.item = [];
	var numItems = recSO.getLineItemCount('item');
	for (var i=1;i <= numItems;i++){
		salesOrderObj.item[i] = new Object();
		salesOrderObj.item[i].item = recSO.getLineItemValue('item','item',i);
		salesOrderObj.item[i].description = recSO.getLineItemValue('item','description',i);
		salesOrderObj.item[i].quantity = recSO.getLineItemText('item','quantity',i);
		salesOrderObj.item[i].custcolfabric = recSO.getLineItemText('item','custcolfabric',i);
		salesOrderObj.item[i].custcolstyle = recSO.getLineItemText('item','custcolstyle',i);
		
	}
	return salesOrderObj;
	//return recSO;
}