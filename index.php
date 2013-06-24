<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Order Form</title>

	<link href="css/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/bootstrap/css/bootstrap.css" rel="stylesheet">

	<script src="js/jquery/jquery-1.9.1.min.js"></script>
	<script src="js/app.js"></script>
</head>

<body>
	<div id="wrap">
		<div class="container">
			<div class="page-header">
				<h1>Web Order Form</h1>
			</div>
			<ol>
				<li>The totals get saved to my Orders database and the items get saved to my OrderItems database.</li>
				<li>I want to calculate the totals on change of quantity (Qty).</li>
				<li>I want to update the order line item with php to sql on qty change.</li>
				<li>If the customer click the delivery option it must show the correct / corresponding total at bottom (not both like my sample)</li>
			</ol>
			<hr>
			<div class="well well-small wellblack">
				<p><strong>Please select delivery option below:</strong></p>
				<label class="radio">
					<input type="radio" value="1" id="OrderDelivery" name="data[Order][delivery]">
					<strong>Yes</strong>, please deliver this order
				</label>
				<label class="radio">
					<input type="radio" value="0" id="OrderDelivery" name="data[Order][delivery]">
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
					<tr>
						<td>XYZ789</td>
						<td>Ut rhoncus purus eu ipsum pretium adipiscing</td>
						<td>R 155.00</td>
						<td><input type="text" value="3" class="input input-small"></td>
						<td>R 465.00</td>
					</tr>
					<tr>
						<td>ABC123</td>
						<td>Fusce mattis in ante cursus pellentesque</td>
						<td>R 177.00</td>
						<td><input type="text" value="2" class="input input-small"></td>
						<td>R 354.00</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right"><strong>Items</strong></span></td>
						<td><strong>5</strong> products</td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right"><strong>Total Ex VAT</strong></span></td>
						<td><strong>R 819.00</strong><small> Excl. VAT</small></td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right"><strong>Delivery Cost</strong></span></td>
						<td><strong>R 50.00</strong><small> Excl. VAT</small></td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right"><strong>Total without delivery</strong></span></td>
						<td><strong>R 819.00</strong><small> Incl. VAT</small></td>
					</tr>
					<tr class="text-info">
						<td colspan="4"><span class="pull-right"><strong>Total with Delivery</strong></span></td>
						<td><strong>R 869.00</strong><small> Incl. VAT</small></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div id="footer">
		<div class="container">
			<p class="muted credit">Example courtesy <a href="https://github.com/abarkhuysen">Arthur Barkhuysen</a>.</p>
		</div>
	</div>
</body>
</html>
