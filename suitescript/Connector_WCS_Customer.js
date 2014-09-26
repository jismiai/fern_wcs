function ConnectorMagentoCustomer_AS(type){
	/*
		This is a Netsuite user event - after submit function.
		When a task is saved and has met specific criteria, this scripts calls external URI to create booking in Magento
	*/
	if (type == 'create' || type =='edit'){
		if (nlapiGetFieldValue('custentity_fern_post_magento') == 'T' && nlapiGetFieldValue('custentity_fern_magento_id') == "" ){
		var url = "http://ec2-54-79-90-156.ap-southeast-2.compute.amazonaws.com/wcs_magento/testapi.php";
		var headers = new Array();
		var customer = {};
				
		customer.netsuite_internalid = nlapiGetRecordId();
		customer.salutation = nlapiGetFieldValue('salutation');
		customer.firstname = nlapiGetFieldValue('firstname');
		customer.lastname = nlapiGetFieldValue('lastname');
		customer.companyname = nlapiGetFieldValue('companyname');
		customer.phone = nlapiGetFieldValue('phone');
		customer.email = nlapiGetFieldValue('email');
		customer.password = nlapiGetFieldValue('custentity_first_time_pwd');
		
		var customerAddresses = new Array();
		var gotBilling = false;
		var gotShipping = false;
		var addressTotal = nlapiGetLineItemCount('addressbook');
		nlapiLogExecution('DEBUG','no address', addressTotal);
		for (i = 1; i <= addressTotal; i++){
			nlapiSelectLineItem('addressbook',i);
			var addressLine = {};
			if (nlapiGetCurrentLineItemValue('addressbook','defaultshipping') == 'T' || nlapiGetCurrentLineItemValue('addressbook','defaultbilling') == 'T'){
				if (nlapiGetCurrentLineItemValue('addressbook','defaultshipping') == 'T'){
					gotShipping = true;
					addressLine.is_default_shipping = true;
				} else {
					addressLine.is_default_shipping = false;
				}
				if (nlapiGetCurrentLineItemValue('addressbook','defaultbilling') == 'T'){
					gotBilling = true;
					addressLine.is_default_billing = true;
				} else {
					addressLine.is_default_billing = false;
				}
				addressLine.addr1 = nlapiGetCurrentLineItemValue('addressbook','addr1');
				addressLine.addr2 = nlapiGetCurrentLineItemValue('addressbook','addr2');
				addressLine.city = nlapiGetCurrentLineItemValue('addressbook','city');
				addressLine.state = nlapiGetCurrentLineItemValue('addressbook','state');
				addressLine.zip = nlapiGetCurrentLineItemValue('addressbook','zip');
				addressLine.phone = nlapiGetCurrentLineItemValue('addressbook','phone');
				if (addressLine.phone == ""){
					addressLine.phone = customer.phone;
				}
				addressLine.country = nlapiGetCurrentLineItemValue('addressbook','country');
				
				customerAddresses.push(addressLine);
			}
		}
		if (addressTotal > 0){
			customer.addresses = customerAddresses;
		}
		
		var body = {};
		body.customer = JSON.stringify(customer);
		body.action = 'create';
		nlapiLogExecution('DEBUG','request data',body.customer);
		var output = nlapiRequestURL(url,body,headers,"POST");
		//nlapiLogExecution('DEBUG','date',booking.booking_date);
		nlapiLogExecution('DEBUG','response',output.getBody());
		
		//var jsonData = JSON.parse(output.getBody());
		/*
		var thisrecord = nlapiLoadRecord('task',nlapiGetRecordId());
		thisrecord.setFieldValue('custevent_fern_connector_magentoid',jsonData.booking_id);
		thisrecord.setFieldValue('custevent_fern_booking_delete_url',jsonData.delete_url);
		nlapiSubmitRecord(thisrecord);
		*/
		}
	}
 }