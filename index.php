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
	<script src="js/app.js"></script>
</head>

<body>
	<div class="container">
		<div class="page-header">
			<h1>Web Order Form</h1>
		</div>
		<ol>
			<li>The totals get saved to my Orders database and the items get saved to my OrderItems database.</li>
			<li class='strikethrough'>I want to calculate the totals on change of quantity (Qty).</li>
			<li class='strikethrough'>I want to update the order line item with php to sql on qty change.</li>
			<li class='strikethrough'>If the customer click the delivery option it must show the correct / corresponding total at bottom (not both like my sample)</li>
		</ol>
		<hr>
		<form>
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
				<span id="minDelivery" class="label label-warning" data-min-delivery="100"> Customer minimun delvery charge R100</span>
			</div>

			<table class="table table-condensed table-hover table-bordered">
				<thead>
					<tr>
						<th>Code</th>
						<th>Features</th>
						<th>Unit Price</th>
						<th>Qty</th>
						<th>Sub Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sqlConnector = new SQL_Connection();
					$orderItems = $sqlConnector->getOrderItems();

					/** Calculate initial values from database data **/
					$total_qty = 0;
					$total_price = 0;

					foreach($orderItems as $orderItem) {
						$total_qty += $orderItem['qty'];
						$total_price += ($orderItem['price'] * $orderItem['qty']);

						echo "<tr data-price='".$orderItem['price']."' data-id='".$orderItem['id']."'>";
						echo "<td>".$orderItem['code']."</td>";
						echo "<td>R ".$orderItem['features']."</td>";
						echo "<td>R ".$orderItem['price']."</td>";
						echo "<td><input type='text' placeholder='0' value='".$orderItem['qty']."' class='input input-small input-amountitems'></td>";
						echo "<td class='fill-in-subtotal'>R<div class='inblock'>". ($orderItem['price'] * $orderItem['qty']) ."</div></td>";
						echo "</tr>";
					}
					
					/** Calculate initial totals from database data **/
					$delivery_cost = ($total_price * 1.10)  - $total_price;
					$total_incl_vat = ($total_price * 1.14);
					$grand_total = ($total_incl_vat + $delivery_cost);
					?>
					
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Items</span></td>
						<td>
							<div class='fillin-numproducts inblock'><?php echo $total_qty; ?></div> 
							product/s
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Total Ex VAT</span></td>
						<td>
							<div class='inblock'>R</div>
							<div class='fillin-exvat inblock'><?php echo number_format($total_price, 2); ?></div>
							<small> Excl. VAT</small>
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Total Incl VAT</span></td>
						<td>
							<div class='inblock'>R</div>
							<div class='fillin-inclvat inblock'><?php echo number_format($total_incl_vat, 2); ?></div>
							<small> Excl. VAT</small>
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Delivery Cost</span></td>
						<td>
							<div class='inblock'>R</div>
							<div class='fillin-delivery inblock'><?php echo number_format($delivery_cost, 2); ?></div>
							<small> Excl. VAT</small>
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right"><strong>Total</strong></span></td>
						<td>
							<div class='strong inblock'>R</div>
							<div class='fillin-totalinclvat strong inblock'><?php echo number_format($grand_total, 2); ?></div>
							<small> Incl. VAT</small>
						</td>
					</tr>
				</tbody>
			</table>
			<button type="submit" class="btn btn-primary">Checkout</button>
		</form>
	</div>

<div id="footer">
	<div class="container">
		<p class="muted credit">Example courtesy <a href="https://github.com/abarkhuysen">Arthur Barkhuysen</a>, 
			<a href="https://github.com/jadekler">Jean Barkhuysen</a>.</p>
		</div>
	</div>
</body>
</html>
