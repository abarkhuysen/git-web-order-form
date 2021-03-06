<?php
include "lib/sql.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Order Form</title>

	<link href="css/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="css/app.css" rel="stylesheet">

	<script src="js/jquery/jquery-1.9.1.min.js"></script>
	<script src="js/bootstrap/js/bootstrap.min.js"></script>
	<script src="js/helpers.js"></script>
	<script src="js/app.js"></script>
</head>

<?php
$sqlConnector = new SQL_Connection();
$orderItems = $sqlConnector->getOrderItems(1);
?>

<body>
	<div class="container">
		<div class="page-header">
			<h1>Web Order Form</h1>
			<iframe src="http://ghbtns.com/github-btn.html?user=abarkhuysen&type=follow&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="165" height="20"></iframe>
		</div>
		<div class="well well-small wellblack">
			<p>Auto search the products table for typed code.</p>
			<form class="form-inline" name="updateOrderItem" action="">
				<input id="codeSearch" name="code" type="text" class="input-xxlarge" placeholder="Type product code to start search" autocomplete="off">
				<input name="qty" id="qty" type="text" class="input-small" placeholder="Quantity">
				<input name="product_id" id="product_id" type="hidden">
				<input name="code" id="code" type="hidden">
				<input name="price" id="price" type="hidden">
				<input name="features" id="features" type="hidden">
				<input name="order_id" id="order_id" type="hidden" value="1">
				<input type="submit" class="btn" id="submitOrderItemForm" value="Add to order">
			</form>
			<span class="help-block error alert alert-error" id="codeSearch_error">The code can not be blank</span>
			<span class="help-block error alert alert-error" id="qty_error">The quantity can not be blank</span>
			<span class="help-block error alert alert-success" id="message">The item was added to order</span>
			<span class="help-block error alert alert-error" id="tableEmpty">No items in this order</span>
		</div>

		<div id="div1">
			<div id="orderItemsList">
				<?php echo "<form class='item-order-form' data-orderid='1'>" ?>
				<table class="table table-condensed table-hover table-bordered" id="tableForm">
					<thead>
						<tr>
							<th>Code</th>
							<th>Features</th>
							<th>Unit Price</th>
							<th>Qty</th>
							<th>Sub Total</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//If no data in order display messages
						if (!empty($orderItems)) {
							/** Calculate initial values from database data **/
							$total_qty = 0;
							$total_price = 0;

							foreach($orderItems as $orderItem) {
								$total_qty += $orderItem['qty'];
								$total_price += ($orderItem['price'] * $orderItem['qty']);

								echo "<tr class='code-item' data-code='".$orderItem['code']."' data-price='".$orderItem['price']."' data-id='".$orderItem['id']."' >";
								echo "<td>".$orderItem['code']."</td>";
								echo "<td>".$orderItem['features']."</td>";
								echo "<td>R ".$orderItem['price']."</td>";
								echo "<td><input type='text' placeholder='0' value='".$orderItem['qty']."' class='input input-small input-amountitems'></td>";
								echo "<td class='fill-in-subtotal'>R <div class='inblock'>". ($orderItem['price'] * $orderItem['qty']) ."</div></td>";
								echo "<td><span class='btn btn-small btn-warning removeRow'><i class='icon-trash'></i></span></td>";
								echo "</tr>";
							}
						} 
						// else
						// {
						// 	echo "<tr class='code-item'>";
						// 	echo "<td colspan='6'><span class='text-error'>No items in this order</span></td>";
						// 	echo "</tr>";
						// }
						?>
					</tbody>
					<tfoot>
						<tr class="text-info">
							<td colspan="4"><span class="pull-right">Items</span></td>
							<td colspan="2">
								<div class='fillin-numproducts inblock'>Loading..</div> 
							</td>
						</tr>
						<tr class="text-info">
							<td colspan="4"><span class="pull-right">Sub Total</span></td>
							<td colspan="2">
								<div class='inblock'>R</div>
								<div class='fillin-subtotal inblock'>Loading..</div>
							</td>
						</tr>
						<tr class="text-info">
							<td colspan="4"><span class="pull-right">Delivery Cost</span></td>
							<td colspan="2">
								<div class='inblock'>R</div>
								<div class='fillin-delivery inblock'>Loading..</div>
							</td>
						</tr>
						<tr class="text-info">
							<td colspan="4"><span class="pull-right">Amount Excl Vat</span></td>
							<td colspan="2">
								<div class='inblock'>R</div>
								<div class='fillin-amountexvat inblock'>Loading..</div>
							</td>
						</tr>
						<tr class="text-info">
							<td colspan="4"><span class="pull-right">Vat</span></td>
							<td colspan="2">
								<div class='inblock'>R</div>
								<div class='fillin-vat inblock'>Loading..</div>
							</td>
						</tr>
						<tr class="text-info">
							<td colspan="4"><span class="pull-right"><strong>Total</strong></span></td>
							<td colspan="2">
								<div class='strong inblock'>R</div>
								<div class='fillin-totalinclvat strong inblock'>Loading..</div>
							</td>
						</tr>
					</tfoot>
				</table>

				<div class="well well-small wellblack">
					<p><strong>Please select delivery option below:</strong></p>
					<label class="radio">
						<input class="radio-delivery radio-yes-delivery" type="radio" name="delivery" checked>
						<strong>Yes</strong>, please deliver this order
					</label>
					<label class="radio">
						<input class="radio-delivery radio-no-delivery" type="radio" name="delivery">
						<strong>No</strong>, we will collect this order
					</label>
					<!-- THIS IS NEEDED AND WILL CHANGE PER CUSTOMER R100 IS JUST A SAMPLE -->
					<span id="minDelivery" class="label label-warning" data-min-delivery="89"> Customer minimun delvery charge R 89.00</span>

					<button type="submit" class="btn btn-primary">Checkout</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="footer">
	<div class="container">
		<p class="muted credit">Example courtesy <a href="https://github.com/abarkhuysen">Arthur Barkhuysen</a>, 
			<a href="https://github.com/jadekler">Jean Barkhuysen</a>.</p>
		</div>
	</div>
</body>
</html>
