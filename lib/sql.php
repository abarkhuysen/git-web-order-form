<?php
class SQL_Connection {
	function getOrders() {
		return $this->querySql('SELECT * FROM orderdemo.orders');
	}

	function getOrderItems() {
		return $this->querySql('SELECT * FROM orderdemo.order_items oi LEFT JOIN orderdemo.items_table it ON oi.product_id = it.product_id');
	}

	function querySql($query) {
		// Establish connection
		$link = mysql_connect('localhost', 'root', '');
		if (!$link) {
			die('Could not connect: ' . mysql_error());
		}

		// Query server
		$result = mysql_query($query);
		$resultArr = array();

		// Collect data
		while ($row = mysql_fetch_assoc($result)) {
			$resultArr[] = $row;
		}

		// Close connection
		mysql_close($link);

		return $resultArr;
	}
}

function printrThis($string) {
	echo "<pre>";print_r($string);echo "</pre>";
}
?>