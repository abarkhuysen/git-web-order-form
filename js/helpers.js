/**
* This function gets all codes on the page
* @return {Array} An array of codes which are currently on page
*/
function getCodesOnPage() {
	var results = new Array();
	$($(".code-item")).each(function() {
		results.push($(this).attr("data-code"));
	});
	return results;
}

/**
* This function calculates subtotals of a row element
* @param  {jQuery Element Reference} elem 	Row element passed by jquery $()
* @return {int}      						Row subtotal
*/
function getSubtotal(elem) {
	var numItems = elem.val();
	var orderCost = parseFloat(elem.parent().parent().attr('data-price'));
	var subtotal = numItems*orderCost;
	return subtotal;
}

/**
* This function calculates total number of items in table
* @return {int} The qty's totaled up
*/
function getTotalItems() {
	var totalItems = 0;
	$('.input-amountitems').each(function() {
		var numItems = parseInt($(this).val());
		if(isNaN(numItems))
			numItems = 0;
		totalItems += numItems;
	});
	return totalItems;
}

/**
* This function calculates total exvat of all items in table
* @return {float} Total exvat of items in table
*/
function getSubTotal() {
	var subTotal = 0;
	$('.input-amountitems').each(function() {
		var orderPrice = parseFloat($(this).parent().parent().attr('data-price'));
		var numItems = parseInt($(this).val());
		if(isNaN(numItems))
			numItems = 0;
		var costExvat = numItems*orderPrice;
		subTotal += costExvat;
	});
	return subTotal;
}

/**
* This function calculates total inclvat of items in table
* @param  {float} subTotal Total exvat of items in table
* @return {float} Total inclvat of items in table
*/
function getAmountExVat(subTotal,totalDelivery) {
	var amountExVat = parseFloat(subTotal) + parseFloat(totalDelivery);
	return parseFloat(amountExVat);
}

/**
* This function calculates total inclvat of items in table
* @param  {float} subTotal Total exvat of items in table
* @return {float} Total inclvat of items in table
*/
function getTotalVat(amountExVat) {
	var vatAmount = (parseFloat(amountExVat) * 1.14 ) - amountExVat;
	return parseFloat(vatAmount);
}

/**
* This function calculates total delivery cost of items in table
* @param  {float} subTotal Total exvat of items in table
* @return {float}            Total delivery cost of items in table
*/
function getTotalDelivery(subTotal) {
	
	/** THIS IS NEEDED AND WILL CHANGE PER CUSTOMER R100 IS JUST A SAMPLE **/
	var minDelivery = $('#minDelivery').attr('data-min-delivery');
	var totalDelivery = minDelivery;

	if($('.radio-yes-delivery').is(':checked')) {
		calcDelivery = (subTotal * 1.05) - subTotal;
		if (calcDelivery > minDelivery)
			totalDelivery = calcDelivery;
	} else {
		totalDelivery = 0.00;
	}
	return totalDelivery;
}

/**
* This function gets the grand total of items in table (using inclvat, delivery)
* @param  {float} amountExVat  Total inclvat of items in table
* @param  {float} vatAmount Total delivery cost of items in table
* @return {float}               Grand total inclvat price of items in table
*/
function getGrandTotal(amountExVat, vatAmount) {
	var grandTotal = parseFloat(amountExVat) + parseFloat(vatAmount);
	return parseFloat(grandTotal);
}

/**
* This function sets the totals at the bottom of the table
* @return void
*/
function updateTotalsInView() {
	var totalItems 		= getTotalItems();
	var subTotal 		= getSubTotal();
	var totalDelivery 	= getTotalDelivery(subTotal);
	var amountExVat 	= getAmountExVat(subTotal,totalDelivery);
	var vatAmount		= getTotalVat(amountExVat);
	var grandTotal 		= getGrandTotal(amountExVat, vatAmount);

	// Fill in the fillins
	$('.fillin-numproducts').text(totalItems);
	$('.fillin-subtotal').text(parseFloat(subTotal).toFixed(2));
	$('.fillin-amountexvat').text(parseFloat(amountExVat).toFixed(2));
	$('.fillin-vat').text(parseFloat(vatAmount).toFixed(2));
	$('.fillin-totalinclvat').text(parseFloat(grandTotal).toFixed(2));
	$('.fillin-delivery').text(parseFloat(totalDelivery).toFixed(2));
}

/**
* This function updates the orders table
* @param  {int} id The id of the orders table row to update
* @return void
*/
function updateOrderTable(id) {
	var totalItems 		= getTotalItems();
	var subTotal 		= getSubTotal();
	var totalDelivery 	= getTotalDelivery(subTotal);
	var amountExVat 	= getAmountExVat(subTotal,totalDelivery);
	var vatAmount		= getTotalVat(amountExVat);
	var grandTotal 		= getGrandTotal(amountExVat, vatAmount);

	$.ajax({
		type: "POST",
		url: "lib/sql.php",
		data: {
			fn: "updateOrder",
			id: id,
			items: totalItems,
			total_ex_vat: subTotal,
			total_incl_vat: amountExVat,
			delivery: totalDelivery,
			grand_total: grandTotal,
			vat: vatAmount
		}
	});
}

/**
* This function updates the order_items table
* @param  {int} id  The id of the row to update
* @param  {int} qty The new item quantity
* @return void
*/
function updateOrderItemTable(id, qty, order_id) {
	$.ajax({
		type: "POST",
		url: "lib/sql.php",
		data: {
			fn: "updateOrderItem",
			id: id,
			qty: qty,
			order_id: order_id
		}
	});
}

/**
* This function sets the auto search results in to the form fields
* @return void
*/
function updateItemFields(data) {
	var result = $.parseJSON(data);
	$('#product_id').val(result[0].id);
	$('#code').val(result[0].code);
	$('#price').val(result[0].price);
	$('#features').val(result[0].features);
}

/**
* This function slides the row up and then remove it from the dom
* @return void
*/
function removeRowFromDom(data) {
	var result = $.parseJSON(data);

	$("tr[data-id='" + result.item_id +"']").fadeOut(1000, function() {
		$(this).remove();
		updateTotalsInView();
	updateOrderTable(result.order_id);
	});

}