function getWebOrderStatus(status_internalid,received,sentdate){
	switch(status_internalid){
	//'pendingFulfillment' fullyBilled pendingApproval partiallyFulfilled pendingBilling pendingBillingPartFulfilled closed
		case 'pendingApproval':
			return 'Order in progress';
			break;
		case 'pendingFulfillment':
		case 'partiallyFulfilled':
		case 'pendingBillingPartFulfilled':
			return 'Production in progress';
			break;
		case 'pendingBilling':
		case 'fullyBilled':
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
