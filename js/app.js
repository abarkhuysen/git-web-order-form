$(document).ready(function() {
	// Initial page setup
	var orderID = $(".item-order-form").attr("data-orderid");
	var zero = parseFloat(0).toFixed(2);
	updateTotalsInView();
	updateOrderTable(orderID);

	// Hide all alert messages
	$('.error').hide();

	// See if table is empty on page load
	if ($('#tableForm tbody tr').length == 0) {
		$('#tableEmpty').fadeIn(500);
	}

	
	// Submit 'Add an item' form
	$("#submitOrderItemForm").click(function(e) {
		e.preventDefault();

		var code = $("input#codeSearch").val();
		var qty = $("input#qty").val();
		var product_id = $("input#product_id").val();
		var price = $("input#price").val();
		var features = $("input#features").val();
		var order_id = $("input#order_id").val();

		if (code == "") {
			$("span#codeSearch_error").fadeIn(500).delay(1000).fadeOut(500);
			$("input#codeSearch").focus();
			return false;
		}         

		if (qty == "") {
			$("span#qty_error").fadeIn(500).delay(1000).fadeOut(500);
			$("input#qty").focus();
			return false;
		}

		// ADD ORDER ITEM TO order_items
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
				// ADD ORDER ITEM TO VIEW
				$('#message').fadeIn(500).delay(1000).fadeOut(500);

				data = JSON.parse(data);
				var id = data[0].id;

				$('#tableForm').children('tbody').append("\
					<tr class='code-item' data-code='"+code+"' data-price='"+price+"' data-id='"+id+"'>\
						<td>"+code+"</td>\
						<td>"+features+"</td>\
						<td>R "+price+"</td>\
						<td><input type='text' placeholder='0' value='"+qty+"' class='input input-small input-amountitems'></td>\
						<td class='fill-in-subtotal'>R <div class='inblock'>"+(price*qty)+"</div></td>\
						<td><span class='btn btn-small btn-warning removeRow'><i class='icon-trash'></i></span></td>\
					</tr>\
					");
				//Clear the typeahead search bar and qty after data insert
				$('#codeSearch').val('');
				$('#qty').val('');
				// UPDATE VIEW TOTALS
				var orderID = $(".item-order-form").attr("data-orderid");
				updateTotalsInView();

				// UPDATE DB TOTALS
				updateOrderTable(orderID);

				//Check if table is empty
				if ($('#tableForm tbody tr').length >= 1) {
						$('#tableEmpty').fadeOut(500);
						updateTotalsInView();
					};
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
					selectors: "*"
				},
				success: function(data) {
					updateItemFields(data);
				}
			});
		}
	});

	// On the click of .removeRow we fade out the row the delete the item from the database
	$(document).on("click", "span.removeRow", function() {
		if( confirm('Are you sure you want to rmove this item?') ) {
			var item_id = $(this).parent().parent().attr('data-id');
			var order_id = $(".item-order-form").attr("data-orderid");
				$.ajax({
					url: "lib/sql.php",
					type: "POST",
					data: {
						fn: "removeProduct",
						item_id: item_id,
						order_id: order_id,
						selectors: ""
					},
					success: function(data) {
						//Added this function to our helps.js
						removeRowFromDom(data);

						if ($('#tableForm tbody tr').length <= 1) {
							$('#tableEmpty').fadeIn(500);
							updateTotalsInView();
						};
					}
				});
		}
	});


});