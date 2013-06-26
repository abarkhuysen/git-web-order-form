<?php

// Checks for POST data from AJAX
if(isset($_POST['fn'])) {
	switch($_POST['fn']) {
		case 'updateOrder': 
			// Safely retrieves POST data from our javascript
			$id = isset($_POST['id']) ? $_POST['id'] : null;
			$items = isset($_POST['items']) ? $_POST['items'] : null;
			$total_ex_vat = isset($_POST['total_ex_vat']) ? $_POST['total_ex_vat'] : null;
			$total_incl_vat = isset($_POST['total_incl_vat']) ? $_POST['total_incl_vat'] : null;
			$delivery = isset($_POST['delivery']) ? $_POST['delivery'] : null;
			$grand_total = isset($_POST['grand_total']) ? $_POST['grand_total'] : null;

			// Update table with our POST data
			$sqlConnector = new SQL_Connection();
			$sqlConnector->updateOrder($id, $items, $total_ex_vat, $total_incl_vat, $delivery, $grand_total);

			break;
		case 'updateOrderItem':
			// Safely retrieves POST data from our javascript
			$id = isset($_POST['id']) ? $_POST['id'] : null;
			$qty = isset($_POST['qty']) ? $_POST['qty'] : null;

			// Update table with our POST data
			$sqlConnector = new SQL_Connection();
			$sqlConnector->updateOrderItem($id, $qty);

			break;
		default:
			break;
	}
}

/**
 * This class handles our SQL connections
 */
class SQL_Connection {
	/**
	 * This function updates the orders table
	 * @param  int $id               The row id that we are modifying
	 * @param  int $items            The amount of items in this order
	 * @param  float $total_ex_vat   The total ex vat for this order
	 * @param  float $total_incl_vat The total incl vat for this order
	 * @param  float $delivery       The delivery charge for this order
	 * @param  float $grand_total    The grand total for this order
	 * @return void
	 */
	function updateOrder($id, $items, $total_ex_vat, $total_incl_vat, $delivery, $grand_total) {
		$this->querySql('
			UPDATE orderdemo.orders 
			SET items="'.$items.'", total_ex_vat="'.$total_ex_vat.'", 
			total_incl_vat="'.$total_incl_vat.'", delivery="'.$delivery.'", grand_total="'.$grand_total.'"
			WHERE id="'.$id.'"');
	}

	/**
	 * This function updates the order_items table
	 * @param  int $id  The row id we are modifying
	 * @param  int $qty The new quantity for this item
	 * @return void
	 */
	function updateOrderItem($id, $qty) {
		$this->querySql('
			UPDATE orderdemo.order_items 
			SET qty="'.$qty.'"
			WHERE id="'.$id.'"');
	}

	/**
	 * This function returns all data from the orders table
	 * @return array Raw data from the orders table
	 */
	function getOrders() {
		return $this->querySql('
			SELECT * 
			FROM orderdemo.orders');
	}

	/**
	 * This function returns data from the order_items table JOINED with items from the items_table
	 * @return array Raw output of order_items table joined with items_table
	 */
	function getOrderItems() {
		return $this->querySql('
			SELECT * 
			FROM orderdemo.order_items oi 
			LEFT JOIN orderdemo.items_table it 
			ON oi.product_id = it.product_id');
	}

	/**
	 * Helper function that handles opening, closing, and parsing data from SQL given a sql query
	 * @param  string $query The sql query to execute on the db
	 * @return array 		 The resultant data from the db
	 */
	function querySql($query) {
		// Establish connection
		$link = mysql_connect('localhost', 'root', '92e32c');
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

/**
 * Helper debug function - does a printr on data with pre tags around it
 * @param  string $string Data to debug
 * @return echo           Data is echoed to screen with pre tags and print_r
 */
function printrThis($string) {
	echo "<pre>";print_r($string);echo "</pre>";
}
?>