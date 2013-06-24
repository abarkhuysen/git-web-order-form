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
	var deliveryCost = 50;

	// Whenever a user does something to a class='radio-delivery', check if radio-yes-delivery was
	// selected and then either add or remove 50 to Delivery Cost
	$('.radio-delivery').change(function() {
		if($('.radio-yes-delivery').is(':checked')) {
			// They DO want delivery - add R50 to delivery cost and recalculate totals
			$('.fillin-delivery').text(deliveryCost);
			setTotals();
		} else {
			// They DO NOT want delivery - subtract R50 from delivery cost and recalculate totals
			$('.fillin-delivery').text(0);
			setTotals();
		}
	});

	// Whenever a user does something to a class='input-amountitems', change the subtotal and page 
	// totals accordingly
	$('.input-amountitems').change(function() {
		// Calculate and set subtotal
		var numItems = parseInt($(this).val());
		var itemID = $(this).parent().parent().attr('data-id');
		var orderCost = parseFloat($(this).parent().parent().attr('data-inclvat'));
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

		if($('.radio-yes-delivery').is(':checked')) {
			totalDelivery = deliveryCost;
		}

		// Iterate through each qty input and add to totalItems, totalExvat, totalInclvat as it goes
		$('.input-amountitems').each(function() {
			// Get the exvat and inclvat from the tr
			// NOTE: this = input, this.parent = td, this.parent.parent = tr
			var orderExvat = parseFloat($(this).parent().parent().attr('data-exvat'));
			var orderInclvat = parseFloat($(this).parent().parent().attr('data-inclvat'));

			// Get the number of items. If it's not a number (NaN) - .e.g if someone put in a letter, then
			// set to 0
			var numItems = parseInt($(this).val());
			if(isNaN(numItems))
				numItems = 0;

			// Sum exvat and inclvat
			var costExvat = numItems*orderExvat;
			var costInclvat = numItems*orderInclvat;

			// Increase totals
			totalItems += numItems;
			totalExvat += costExvat;
			totalInclvat += costInclvat;
		});

		// Fill in the fillins
		$('.fillin-numproducts').text(totalItems);
		$('.fillin-exvat').text(totalExvat);
		$('.fillin-inclvat').text(totalInclvat);
		$('.fillin-totalinclvat').text(totalInclvat+totalDelivery);
	}
});