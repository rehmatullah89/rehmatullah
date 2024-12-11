<?php
session_start();
//Include database connection details
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
	
	if(isset($_POST)){
	 // echo "<pre>";print_r($_POST);
	 // echo "---------------------------";
	 // echo "<pre>";print_r($_SESSION['cart']);
	 // die();
		$j=1;
		$arr = array();
		$arr2 = array();
		$customer = $_POST['customers']; // customer Name
		$Address = $_POST['caddress']; //Customer Address
		$Phone = $_POST['cphone']; //Customer Phone no.
		


$l=0;
$cat = 0;
$item= 0;
$bill_no =0;
$total_amount = 0;
$total_qty = 0;
$orig_total_amount = 0;
		foreach($_SESSION['cart'] as $arr_vals){
			$k=0;
			$box_quantity =0;
			$item_quantity =0;
			$l++;
			$item= $arr_vals[0]; 
			$cat= $arr_vals[1];
			$quantity= $arr_vals[2];
			$total_qty = $total_qty + $quantity;
			$orig_item_amount= $arr_vals[3];
			$amount= $arr_vals[4];
			$per_box_items= $arr_vals[5];
			$item_box_chk_pt= $arr_vals[6];
			
			if($item_box_chk_pt == 1 && !is_float($quantity)){
				$item_quantity = $quantity;
				$box_quantity =0;
			}
			
			if($item_box_chk_pt == 0 && !is_float($quantity)){
				$item_quantity = 0;
				$box_quantity = $quantity;
			}
			
			if(is_float($quantity))
			{
				$box_quantity = floor($arr_vals[2]);
				$item_quantity = ($arr_vals[2] - $box_quantity)*10;
			}
			// else
			// $box_quantity = $quantity;

			
			$q3="SELECT * FROM products where series_name='$cat' and item_name='$item'";
			$result = mysql_query($q3);
			while($row=mysql_fetch_array($result)){
			$per_box_items = $row['per_box_items']; //no of items in a box
			$unit_price = $row['unit_price']; //unit price of item
			$cotton_quantity = $row['cotton_quantity']; //no of cottons available
			$cotton_boxses = $row['cotton_boxses']; //no of boxes in cotton
			$remaining_box_qty = $row['remaining_box_qty']; //boxes without cotton/in open cotton
			$remaining_item_qty = $row['remaining_item_qty']; //items without boxes/in open box
			// $item_val_arr = $quantity; //number of items(boxes) ordered
			$per_item_amount = $amount ; //per pice(item) price
			$total_amount = $total_amount + $per_item_amount;
			$orig_total_amount = $orig_total_amount + $orig_item_amount;
			}
			
			/* update inventory */
			$toatl_boxes_available = $cotton_quantity*$cotton_boxses + $remaining_box_qty;
			$toatl_items_available = ($toatl_boxes_available*$per_box_items)+$remaining_item_qty;
			 	
			$toatl_remaining_boxes = $toatl_boxes_available - $box_quantity;
			$toatl_remaining_items = $toatl_items_available - $item_quantity;
			
			$left_items_in_inventory = ($toatl_remaining_boxes*$per_box_items)+ $toatl_remaining_items;
			/* items to boxes and items conversion */
			$quotient_boxes = intval($left_items_in_inventory / $per_box_items);
			$remainder_items = $left_items_in_inventory % $per_box_items;
			/* ***************** */
			/* boxes to cottons conversion */
			$quotient_cottonts = intval($quotient_boxes / $cotton_boxses);
			$remainder_boxes = $quotient_boxes % $cotton_boxses;
			
			$qu="UPDATE products SET cotton_quantity='$quotient_cottonts' ,remaining_box_qty='$remainder_boxes' ,remaining_item_qty='$remainder_items' where series_name='$cat' and item_name='$item'";
			mysql_query($qu);
			// /* --------- */
			
			/* Create new bill */
				if($l == 1) // create bill no
				{
					$bill_no = (mysql_result(mysql_query("SELECT MAX(bill_no) as bill_no FROM customers_bills"),0))+1;
				}
			//insert bill items in details bill table
				$q_bd = "INSERT INTO cbill_details(series_name,item_name,no_boxes_ordered,no_items_ordered,box_item_amt,bill_no) VALUES ('$cat','$item','$box_quantity','$item_quantity','$per_item_amount',$bill_no)";
				mysql_query($q_bd);
			
			
		}
		
		if($total_amount > 0){
		date_default_timezone_set('Asia/Karachi');
		$today = date("Y-m-d H:i:s");
		$q_db = "INSERT INTO customers_bills (bill_no,customer_name,customer_phone,customer_address,total_amount,date_bill,bill_status) VALUES ($bill_no,'$customer','$Phone','$Address','$total_amount','$today',0)";
		mysql_query($q_db);
		}
		/* ------------- */
		
	}
	 
	header("Location: unpaid_customer_bills.php");
?>	
