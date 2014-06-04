function UE_updateSOshipStatus(type){
	if (type == 'create' || type =='edit' || type=='delete'){
		var createdFrom = nlapiGetFieldValue('createdfrom');
		var shipStatus =  nlapiGetFieldValue('shipstatus');
		if (createdFrom != null && shipStatus == 'C'){ // C stands for shipped
			var createdFromText = nlapiGetFieldText('createdfrom');
			createdFromName = createdFromText.substring(0,11); 
			if (createdFromName == 'Sales Order'){ 
				var so_webSentDate = nlapiGetFieldValue('trandate');
				var so_webTrackingNumber = nlapiGetFieldValue('custbodytrackingnumber'); 
				if (type == 'delete'){
					so_webSentDate = '';
					so_webTrackingNumber = '';
				}
				var recSO = nlapiLoadRecord('salesorder', createdFrom);
				recSO.setFieldValue('custbody_website_tracking_number', so_webTrackingNumber);
				recSO.setFieldValue('custbody_website_sent_date', so_webSentDate);
				nlapiSubmitRecord(recSO);
				nlapiLogExecution('AUDIT',"Execute on Sales Order","Script run on Sales order. Type="+type+". On "+createdFromText);
			} else {
				nlapiLogExecution('AUDIT',"Rejected","Fulfillment not created from sales order. internalID : "+nlapiGetRecordId());
			}
		} else {
			nlapiLogExecution('AUDIT',"Rejected","Fulfillment not created from sales order or not shipped. internalID : "+nlapiGetRecordId());
		}
	}
}