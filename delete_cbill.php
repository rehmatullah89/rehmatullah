<?php
error_reporting(0);
require_once('config.php');
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die('Unable to select database');
	}

	$bill_no = $_GET['id'];
	$bill_info = mysql_query("Select * from customers_bills where bill_no=".$bill_no."");
	$bill_details = mysql_query("Select * from cbill_details where bill_no=".$bill_no."");
	while ($row = mysql_fetch_array($bill_details)) {
		$item_id = mysql_result(mysql_query("SELECT item_id  from products where item_name='".$row['item_name']."' AND series_name='".$row['series_name']."'"), 0);
		$box_item = mysql_fetch_object(mysql_query("SELECT remaining_box_qty,remaining_item_qty from products where item_id=".$item_id.""));
		mysql_query("UPDATE products set remaining_box_qty=".($row['no_boxes_ordered']+$box_item->remaining_box_qty).",remaining_item_qty=".($row['no_items_ordered']+$box_item->remaining_item_qty)." where item_id=".$item_id."");
	}
mysql_query("DELETE FROM cbill_details WHERE bill_no=".$bill_no."");
mysql_query("DELETE FROM customers_bills WHERE bill_no=".$bill_no."");
header('Location: unpaid_customer_bills.php');
?>