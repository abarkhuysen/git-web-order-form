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

					foreach($orderItems as $orderItem) {
						echo "<tr data-inclvat='".$orderItem['incl_vat']."' data-exvat='".$orderItem['ex_vat']."'>";
						echo "<td>".$orderItem['code']."</td>";
						echo "<td>R ".$orderItem['features']."</td>";
						echo "<td>R ".$orderItem['incl_vat']."</td>";
						echo "<td><input type='text' placeholder='0' class='input input-small input-amountitems'></td>";
						echo "<td class='fill-in-subtotal'>R<div class='inblock'>0</div></td>";
						echo "</tr>";
					}
					?>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Items</span></td>
						<td>
							<div class='fillin-numproducts inblock'>0</div> 
							products
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Total Ex VAT</span></td>
						<td>
							<div class='inblock'>R</div>
							<div class='fillin-exvat inblock'>00</div>
							<small> Excl. VAT</small>
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Total Incl VAT</span></td>
						<td>
							<div class='inblock'>R</div>
							<div class='fillin-inclvat inblock'>00</div>
							<small> Excl. VAT</small>
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right">Delivery Cost</span></td>
						<td>
							<div class='inblock'>R</div>
							<div class='fillin-delivery inblock'>50</div>
							<small> Excl. VAT</small>
						</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right"><strong>Total</strong></span></td>
						<td>
							<div class='strong inblock'>R</div>
							<div class='fillin-totalinclvat strong inblock'>50</div>
							<small> Incl. VAT</small>
						</td>
					</tr>
				</tbody>
			</table>
			<button type="submit" class="btn btn-primary">Checkout</button>
		</form>
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
