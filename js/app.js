/**
 * jQUERY NOTES:
 *
 * 1. $('.foo') -> The period means it is looking for a class (e.g. <div class='foo'>)
 *    $('#foo') -> The hash means it is looking for an ID (e.g. <div id='foo'>)
 *
 * 2. $('.foo') -> If there are multiple foo classes (e.g. <td class='foo'></td> <td class='foo'></td>), then
 *    jquery will do whatever follows to ALL of them. E.g. if all my rows in a table have class 'foo', then
 *    $('.foo').change(<<whatever>>) will do <<whatever>> to each tr (that has 'foo')
 */

// This tells javascript to wait until the document is finished loading before executing. Very important!
$(document).ready(function(){

	// Whenever a user does something to a class='radio-delivery', check if radio-yes-delivery was
	// selected and then recalculate totals
	$('.radio-delivery').change(function() {
		if($('.radio-yes-delivery').is(':checked')) {
			setTotals();
		} else {
			setTotals();
		}
	});

	// Whenever a user does something to a class='input-amountitems', change the subtotal and page 
	// totals accordingly
	$('.input-amountitems').change(function() {
		// Calculate and set subtotal
		var numItems = parseInt($(this).val());
		var itemID = $(this).parent().parent().attr('data-id');
		var orderCost = parseFloat($(this).parent().parent().attr('data-price'));
		var subtotal = numItems*orderCost;
		$(this).parent().siblings('.fill-in-subtotal').children('div').text(subtotal);

		// Set totals
		setTotals();
	});

	/**
	 * This function polls the entire form, looking at each row's inclvat, exvat, and qty selected. It sums
	 * the totals and then sets the summation data (Items, Total Ex VAT, Total Incl VAT, Total)
	 */
	function setTotals() {
		var totalItems = 0;
		var totalExvat = 0;
		var totalInclvat = 0;
		var totalDelivery = 0;
		var deliveryCost = 0;
		var minDelivery = 0;
		var calcDelivery = 0;
		var grandTotal = 0;

		// Iterate through each qty input and add to totalItems, totalExvat, totalInclvat as it goes
		$('.input-amountitems').each(function() {
			// Get the exvat and inclvat from the tr
			// NOTE: this = input, this.parent = td, this.parent.parent = tr
			var orderPrice = parseFloat($(this).parent().parent().attr('data-price'));

			// Get the number of items. If it's not a number (NaN) - .e.g if someone put in a letter, then
			// set to 0
			var numItems = parseInt($(this).val());
			if(isNaN(numItems))
				numItems = 0;

			// Sum qty * price
			var costExvat = numItems*orderPrice;
			var costInclvat = parseFloat((costExvat * 1.14));

			// Increase totals
			totalItems += numItems;
			totalExvat += costExvat;
			totalInclvat += costInclvat;
		});

		// Whenever a user does something to a class='radio-delivery', check if radio-yes-delivery was
		// selected and then calculate delivery accordingly
		if($('.radio-yes-delivery').is(':checked')) {
			// They DO want delivery - add deliveryCost
			// Get customer minimum delivery charge
			minDelivery = $('#minDelivery').attr('data-min-delivery');
			calcDelivery = (totalExvat * 1.10) - totalExvat;

			// Check to see if calculated delivery is more than customer minimum delivery charge and set accordingly
			if (calcDelivery > minDelivery) {
				totalDelivery = calcDelivery;
			} else {
				totalDelivery = minDelivery;
			}
			$('.fillin-delivery').text(totalDelivery);

		} else {
			// They DO NOT want delivery - subtract deliveryCost 
			$('.fillin-delivery').text('0.00');
		}

		// Set the grand total after delivery been calculated
		grandTotal = totalInclvat+totalDelivery;

		// Fill in the fillins
		$('.fillin-numproducts').text(totalItems);
		$('.fillin-exvat').text(totalExvat.toFixed(2));
		$('.fillin-inclvat').text(totalInclvat.toFixed(2));
		$('.fillin-totalinclvat').text(grandTotal.toFixed(2));
	}
});