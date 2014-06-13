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
	Customer.isSuccess = true;
	return Customer;
}