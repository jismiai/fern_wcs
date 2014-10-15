function Connector_Export_Item_Magento_AS(type){
	/*
	This is a user-event script function, deployed on items
	This will post saved item to Magento if criteria is met.
	*/
	if (type == 'create' || type == 'edit'){
		var thisItemType = nlapiGetRecordType();
		var thisMatrixType = nlapiGetFieldValue('matrixtype');
		
		nlapiLogExecution('DEBUG','item type',thisItemType);
		nlapiLogExecution('DEBUG','Matrix type',thisMatrixType);
		if (thisItemType == 'inventoryitem'){
			if (thisMatrixType == null || thisMatrixType =='child' || thisMatrixType == '') {
				
				var priceID = 'price1'; //base currency of the account
			
				var thisItem = {};
				
				//item info essential export
				thisItem.sku = nlapiGetFieldValue('itemid');
				thisItem.product_name = nlapiGetFieldValue('displayname');
				thisItem.product_description = nlapiGetFieldValue('salesdescription');
				thisItem.product_type = nlapiGetFieldValue('custitemfinalgoodtype');
				
				//pricing export
				var normalPriceLevel = 5;
				var specialPriceLevel = 5;
				var quantityLevels = nlapiGetMatrixCount(priceID, 'price');
				var priceLevels = nlapiGetLineItemCount(priceID);
				var onlinePrice = nlapiGetLineItemMatrixValue(priceID,'price',normalPriceLevel,1);
				nlapiLogExecution('DEBUG','online price',onlinePrice);
				nlapiLogExecution('DEBUG','levels','quantity: '+quantityLevels+', prices:'+priceLevels);
				//thisItem.normal_price = nlapiGetMatrixField(price1, fldnam, column);
				
				//attribute sets?
				
				//image export
				var imageInternalId = nlapiGetFieldValue('storedisplayimage');
				if (imageInternalId != '' && imageInternalId != null){
					var file = nlapiLoadFile(imageInternalId);
					if (file.isOnline()){
						thisItem.product_image = file.getURL();
					}
				}				
				nlapiLogExecution('DEBUG','this item json',JSON.stringify(thisItem));
			} else {
				nlapiLogExecution('DEBUG','Matrix if','this is not a matrix');
			}
		}
	}
}