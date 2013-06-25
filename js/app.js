$(document).ready(function() {
	var orderID = $(".item-order-form").attr("data-orderid");

	// Set initial values
	if($('.radio-yes-delivery').is(':checked')) {
		var totalExvat = getTotalExvat();
		var totalDelivery = getTotalDelivery(totalExvat);
		$('.fillin-delivery').text(totalDelivery.toFixed(2));
	} else {
		$('.fillin-delivery').text(0);
	}
	updateTotalsInView();
	updateOrderTable(orderID);

	// Change delivery + totals + update DB whenever user clicks delivery button
	$('.radio-delivery').change(function() {
		if($('.radio-yes-delivery').is(':checked')) {
			var totalExvat = getTotalExvat();
			var totalDelivery = getTotalDelivery(totalExvat);

			$('.fillin-delivery').text(totalDelivery);
			updateTotalsInView();
			updateOrderTable(orderID);
		} else {
			$('.fillin-delivery').text(0);
			updateTotalsInView();
			updateOrderTable(orderID);
		}
	});

	// Change subtotal + totals + update DB whenever user changes qty
	$('.input-amountitems').change(function() {
		var itemID = $(this).parent().parent().attr('data-id');
		var subtotal = getSubtotal($(this));

		$(this).parent().siblings('.fill-in-subtotal').children('div').text(subtotal);
		updateTotalsInView();
		updateOrderTable(orderID);
		updateOrderItemTable(itemID, $(this).val());
	});

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
	 function getTotalExvat() {
	 	var totalExvat = 0;
	 	$('.input-amountitems').each(function() {
	 		var orderPrice = parseFloat($(this).parent().parent().attr('data-price'));
	 		var numItems = parseInt($(this).val());
	 		if(isNaN(numItems))
	 			numItems = 0;
	 		var costExvat = numItems*orderPrice;
	 		totalExvat += costExvat;
	 	});
	 	return totalExvat;
	 }

	/**
	 * This function calculates total inclvat of items in table
	 * @param  {float} totalExvat Total exvat of items in table
	 * @return {float} Total inclvat of items in table
	 */
	 function getTotalInclvat(totalExvat) {
	 	return parseFloat((totalExvat * 1.14));
	 }

	/**
	 * This function calculates total delivery cost of items in table
	 * @param  {float} totalExvat Total exvat of items in table
	 * @return {float}            Total delivery cost of items in table
	 */
	 function getTotalDelivery(totalExvat) {
	 	var minDelivery = 100;
	 	var totalDelivery = minDelivery;
	 	if($('.radio-yes-delivery').is(':checked')) {
	 		calcDelivery = (totalExvat * 1.10) - totalExvat;
	 		if (calcDelivery > minDelivery)
	 			totalDelivery = calcDelivery;
	 	} else {
	 		totalDelivery = 0;
	 	}
	 	return totalDelivery;
	 }

	/**
	 * This function gets the grand total of items in table (using inclvat, delivery)
	 * @param  {float} totalInclvat  Total inclvat of items in table
	 * @param  {float} totalDelivery Total delivery cost of items in table
	 * @return {float}               Grand total inclvat price of items in table
	 */
	 function getGrandTotal(totalInclvat, totalDelivery) {
	 	return totalInclvat+totalDelivery;
	 }

	/**
	 * This function sets the totals at the bottom of the table
	 * @return void
	 */
	 function updateTotalsInView() {
	 	var totalItems = getTotalItems();
	 	var totalExvat = getTotalExvat();
	 	var totalInclvat = getTotalInclvat(totalExvat);
	 	var totalDelivery = getTotalDelivery(totalExvat);
	 	var grandTotal = getGrandTotal(totalInclvat, totalDelivery);

		// Fill in the fillins
		$('.fillin-numproducts').text(totalItems);
		$('.fillin-exvat').text(totalExvat.toFixed(2));
		$('.fillin-inclvat').text(totalInclvat.toFixed(2));
		$('.fillin-totalinclvat').text(grandTotal.toFixed(2));
	}

	/**
	 * This function updates the orders table
	 * @param  {int} id The id of the orders table row to update
	 * @return void
	 */
	function updateOrderTable(id) {
		var totalItems = getTotalItems();
	 	var totalExvat = getTotalExvat();
	 	var totalInclvat = getTotalInclvat(totalExvat);
	 	var totalDelivery = getTotalDelivery(totalExvat);
	 	var grandTotal = getGrandTotal(totalInclvat, totalDelivery);

		$.ajax({
			type: "POST",
			url: "lib/sql.php",
			data: {
				fn: "updateOrder",
				id: id,
				items: totalItems,
				total_ex_vat: totalExvat,
				total_incl_vat: totalInclvat,
				delivery: totalDelivery,
				grand_total: grandTotal
			}
		});
	}

	/**
	 * This function updates the order_items table
	 * @param  {int} id  The id of the row to update
	 * @param  {int} qty The new item quantity
	 * @return void
	 */
	function updateOrderItemTable(id, qty) {
		$.ajax({
			type: "POST",
			url: "lib/sql.php",
			data: {
				fn: "updateOrderItem",
				id: id,
				qty: qty
			}
		});
	}
});