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
			$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : null;

			// Update table with our POST data
			$sqlConnector = new SQL_Connection();
			$sqlConnector->updateOrderItem($id, $qty, $order_id);
			break;

		case 'findProduct':
			$code = isset($_POST['code']) ? $_POST['code'] : null;
			$selectors = isset($_POST['selectors']) ? $_POST['selectors'] : null;

			// Do the search in our products table
			$sqlConnector = new SQL_Connection();
			$sqlConnector->findProduct($code, $selectors);
		break;

		case 'insertOrderItem':
			// Safely retrieves POST data from our javascript
			$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : null;
			$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
			$price = isset($_POST['price']) ? $_POST['price'] : null;
			$qty = isset($_POST['qty']) ? $_POST['qty'] : null;

			// Do the insert in to order_items table
			$sqlConnector = new SQL_Connection();
			$sqlConnector->insertOrderItem($order_id, $product_id, $price, $qty);
		break;

		case 'removeProduct':
			// Safely retrieves POST data from our javascript
			$id = isset($_POST['id']) ? $_POST['id'] : null;

			// Do the delete in order_items table where id = passed id
			$sqlConnector = new SQL_Connection();
			$sqlConnector->removeOrderItem($id);
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
			WHERE id="'.$id.'"',
			'update');
	}

	/**
	 * This function updates the order_items table
	 * @param  int $id  The row id we are modifying
	 * @param  int $qty The new quantity for this item
	 * @return void
	 */
	function updateOrderItem($id, $qty, $order_id) {
		$this->querySql('
			UPDATE orderdemo.order_items 
			SET qty = "'.$qty.'"
			WHERE id = "'.$id.'"
			AND order_id = "'.$order_id.'"', 
			'update');
	}

	/**
	 * This function inserts new product items in to the order_items table
	 * @param  int $order_id  The order id we are inserting
	 * @param  int $product_id  The product id we are inserting
	 * @param  int $price  The price we are inserting
	 * @param  int $qty The quantity we are inserting
	 * @return void
	 */
	function insertOrderItem($order_id, $product_id, $price, $qty) {
		$this->querySql('
			INSERT INTO orderdemo.order_items (`order_id`, `product_id`, `price`, `qty` ) 
			VALUES ("'.$order_id.'", "'.$product_id.'", "'.$price.'", "'.$qty.'")',
			'update');

		$rows = $this->querySql('
			SELECT id
			FROM orderdemo.order_items
			WHERE order_id = "'.$order_id.'"
			AND product_id = "'.$product_id.'"
			AND price = "'.$price.'"
			AND qty = "'.$qty.'"',
			'select');

		echo json_encode($rows);
	}

	/**
	 * This function remove the select order item form the table via ajax
	 * @param  int $id The order item id passed to be deleted
	 * @return int $id The order item id passed to be used by our java to remove the row from the form
	 */
	function removeOrderItem($id) {
		$this->querySql('
			DELETE FROM `orderdemo`.`order_items` 
			WHERE `order_items`.`id` = '.$id,
			'update'
		);
		//This is pasing the id back to our ajax to remove the current tavle row from the form/dom
		echo $id;
	}

	/**
	 * This function returns all data from the orders table
	 * @return array Raw data from the orders table
	 */
	function getOrders() {
		return $this->querySql('
			SELECT * 
			FROM orderdemo.orders',
			'select');
	}

	/**
	 * This function returns data from the order_items table JOINED with items from the products where our order_id equals the passed id
	 * @param  [type] $order_id [description]
	 * @return array Raw output of order_items table joined with products
	 */
	function getOrderItems($order_id) {
		//Our previous query selected all the fields from both databases and used the wrong id(from products) to update the order_items data
		return $this->querySql("
			SELECT oi.id, oi.price, oi.qty, oi.order_id, pr.code, pr.features 
			FROM orderdemo.order_items oi 
			LEFT JOIN orderdemo.products pr 
			ON oi.product_id = pr.id
			WHERE oi.order_id = {$order_id}", "select"
			);
	}

	/**
	 * This function returns data from the products table to be displayed in the type ahead field
	 * @return array Raw output of products table
	 */
	function findProduct($code, $selectors) {
		$rows = $this->querySql("
			SELECT {$selectors}
			FROM orderdemo.products 
			WHERE code LIKE '%{$code}%'",
			'select');

		// Only returning 1 field to bootstrap typeahead if selector is code
		if($selectors == "code") {
			foreach($rows as $product) {
				$output[] = $product['code'];
			} 
			echo json_encode($output);
		} else {
			echo json_encode($rows);
		}
	}

	/**
	 * Helper function that handles opening, closing, and parsing data from SQL given a sql query
	 * @param  string $query The sql query to execute on the db
	 * @return array 		 The resultant data from the db
	 */
	function querySql($query, $type) {

		// Establish connection
		$link = mysql_connect('localhost', 'root', '92e32c');
		if (!$link) {
			die('Could not connect: ' . mysql_error());
		}

		switch ($type) {
			case 'select':
				// Query server
				$result = mysql_query($query);
				$resultArr = array();

				// Collect data
				while ($row = mysql_fetch_assoc($result)) {
					$resultArr[] = $row;
				}

				return $resultArr;
				break;

			case 'update':
				mysql_query($query);
				break;

			default:
				# code...
				break;
		}
		
		// Close connection
		mysql_close($link);

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