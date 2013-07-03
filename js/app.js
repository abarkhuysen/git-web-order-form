$(document).ready(function() {
	var orderID = $(".item-order-form").attr("data-orderid");
	var zero = parseFloat(0).toFixed(2);

	updateTotalsInView();
	updateOrderTable(orderID);
	$('.error').hide();

	$("#submitOrderItemForm").click(function() {
		// Set values to be passed to validatuion and ahax
		var codeSearch = $("input#codeSearch").val();
		var qty = $("input#qty").val();
		var product_id = $("input#product_id").val();
		var price = $("input#price").val();
		var order_id = $("input#order_id").val();

		if (codeSearch == "") {
			$("span#codeSearch_error").fadeIn(500).delay(1000).fadeOut(500);
			$("input#codeSearch").focus();
			return false;
		}         

		if (qty == "") {
			$("span#qty_error").fadeIn(500).delay(1000).fadeOut(500);
			$("input#qty").focus();
			return false;
		}

		$.ajax({
			cache: false,
			type: "POST",
			url: "lib/sql.php",
			data: {
				fn: "insertOrderItem",
				order_id: order_id, 
				product_id: product_id, 
				price: price, 
				qty: qty
			},
			success: function(data) {
				console.dir(data);
				$('#message').fadeIn(500).delay(1000).fadeOut(500);
				//$("#div1").load("index.php #orderItemsList");
			}
		});
	});

	// Change delivery + totals + update DB whenever user clicks delivery button
	$('.radio-delivery').change(function() {
		if($('.radio-yes-delivery').is(':checked')) {
			var subTotal = getSubTotal();
			var totalDelivery = getTotalDelivery(subTotal);
			updateTotalsInView();
			updateOrderTable(orderID);
			$('.fillin-delivery').text(parseFloat(totalDelivery).toFixed(2));
		} else {
			updateTotalsInView();
			updateOrderTable(orderID);
			$('.fillin-delivery').text(zero);
		}
	});

	// Change subtotal + totals + update DB whenever user changes qty
	$('.input-amountitems').change(function() {
		var itemID = $(this).parent().parent().attr('data-id');
		var subtotal = getSubtotal($(this));

		$(this).parent().siblings('.fill-in-subtotal').children('div').text(subtotal);
		updateTotalsInView();
		updateOrderTable(orderID);
		updateOrderItemTable(itemID, $(this).val(), 1);
	});

	// Bootstrap typeahead (auto complete)
	$("#codeSearch").typeahead({
		source: function(query, process) {
			$.ajax({
				url: "lib/sql.php",
				type: "POST",
				data:  {
					fn: "findProduct",
					code: query,
					selectors: "code"
				},
				dataType: "JSON",
				async: true,
				success: function(data) {
					var codesOnPage = getCodesOnPage();
					var dataWithoutDuplicates = new Array();

					// Filtering out duplicates
					for (var i = 0; i < data.length; i++) {
						if(codesOnPage.indexOf(data[i]) == -1) {
							dataWithoutDuplicates.push(data[i]);
						}
					}

					process(dataWithoutDuplicates); 
				}
			});
		},
		minLength: 1
	});

	// When we get a 4+ digit change on codesearch (e.g. when typeahead returns a code), then go look up that code's details
	$("#codeSearch").change(function() {
		var newVal = $(this).val();
		if(newVal.length >= 4) {
			$.ajax({
				url: "lib/sql.php",
				type: "POST",
				data: {
					fn: "findProduct",
					code: newVal,
					selectors: "id,code,price"
				},
				success: function(data) {
					updateItemFields(data);
				}
			});
		}
	});
});