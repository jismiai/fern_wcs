/** This file includes function allows CRUD operation for task record type
	The suitescript type are Restlet.
	Create date 20,Oct, 2014
**/

function getTrialFit(datain){
	nlapiLogExecution('DEBUG','Request headertype','GET');
	nlapiLogExecution('DEBUG','Content',JSON.stringify(datain));
	
	var output = {};
	//createSearch to getCustomerInternal ID
	var filters = new Array();
	filters[0] = new nlobjSearchFilter('entityid', null, 'is',datain.id);
	var columns = new Array();
	columns[0] = new nlobjSearchColumn('internalid');
	//search filters
	var customerSearch = nlapiSearchRecord('customer',null,filters,columns);
	var customerInternalid ='';
	if (customerSearch != null){
		var customerInternalid = customerSearch[0].getId();
	}
	if (customerInternalid != ''){
		var filters = new Array();
		filters[0] = new nlobjSearchFilter('custeventtasktype',null,'anyof',2); // 2 is trial fitting
		filters[1] = new nlobjSearchFilter('entity','custevent1','anyof',customerInternalid);
		var columns = new Array();
		columns[0] = new nlobjSearchColumn('internalid');
		var taskSearch = nlapiSearchRecord('task',null,filters,columns);
		if (taskSearch != null){
			var taskInternalid = taskSearch[0].getId();
		} else {
			var taskInternalid = null;
		}
	} else {
		output.taskid = 'none';
	}
	
	output.type = 'get';
	output.taskid = taskInternalid;
	return output;
}

function updateTrialFit(datain){
	nlapiLogExecution('DEBUG','Request headertype','POST');
	nlapiLogExecution('DEBUG','Content',JSON.stringify(datain));
	var output = {};
	output.type = 'post';
	return output;
}

function deleteTrialFit(datain){
	nlapiLogExecution('DEBUG','Request headertype','Delete');
	nlapiLogExecution('DEBUG','Content',JSON.stringify(datain));	
}