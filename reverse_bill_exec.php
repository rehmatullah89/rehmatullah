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
	  /*echo "<pre>";print_r($_POST);
	  echo "---------------------------";	
	  echo "<pre>";print_r($_SESSION['cart']);
	die();*/
		$j=1;
		$arr = array();
		$arr2 = array();
		$Distributor = $_POST['Distributor'];
		$customer = "";
		if (isset($_POST['customers']))
			$customer = $_POST['customers'];


$l=0;
$cat = 0;
$item= 0;
$bill_no =0;
$total_amount = 0;
$total_qty = 0;
$orig_total_amount = 0;
$dist_type="";
$sbill_no=0;
$dbill_no=0;

		foreach($_SESSION['cart'] as $arr_vals){
			$k=0;
			$l++;
			$box_quantity =0;
			$item_quantity =0;
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
			
		
			$q3="SELECT * FROM products where series_name='$cat' and item_name='$item'";
			$result = mysql_query($q3);
			$orignal_amount_total = 0;
			while($row=mysql_fetch_array($result)){
			$per_box_items = $row['per_box_items']; //no of items in a box
			$unit_price = $row['unit_price']; //unit price of item
			$cotton_quantity = $row['cotton_quantity']; //no of cottons available
			$cotton_boxses = $row['cotton_boxses']; //no of boxes in cotton
			$remaining_box_qty = $row['remaining_box_qty']; //boxes without cotton/in open cotton
			$remaining_item_qty = $row['remaining_item_qty']; //items without boxes/in open box
			//$item_val_arr = $quantity; //number of items(boxes) ordered
			$per_item_amount = $amount ;
			$total_amount = $total_amount + $per_item_amount;
			$orig_total_amount = $orig_total_amount + $orig_item_amount;
			$orignal_amount_total = ($row['unit_price']*$item_quantity)+($row['unit_price']*$item_quantity*$row['per_box_items']);
			
			}
			
			/* update inventory */
			$toatl_boxes_available = $cotton_quantity*$cotton_boxses + $remaining_box_qty;
			$toatl_items_available = ($toatl_boxes_available*$per_box_items)+$remaining_item_qty;

			$toatl_remaining_boxes = $toatl_boxes_available - $box_quantity;
			$toatl_remaining_items = $toatl_items_available - $item_quantity;
			$left_items_in_inventory = ($toatl_remaining_boxes*$per_box_items)+ $toatl_remaining_items;

			$remainder_items = $remaining_box_qty+$box_quantity;
			$remainder_boxes = $remaining_item_qty+$item_quantity;
			
			 $qu="UPDATE products SET remaining_box_qty='$remainder_boxes' ,remaining_item_qty='$remainder_items' where series_name='$cat' and item_name='$item'";
			 mysql_query($qu);
			// /* --------- */
			/* Create new bill */
				if($l == 1) // create bill no
				{
					$bill_no = mysql_result(mysql_query("SELECT MAX(bill_no) as bill_no FROM reverse_bills"),0)+1;
					$qry = mysql_query("SELECT distributor_for FROM distributors_list where Name_distributor='".$Distributor."'");
					while ($row = mysql_fetch_array($qry)) {
						$dist_type = $row['distributor_for'];
					}
					 if($dist_type == 'sanva'){
					 	$sbill_no = (mysql_result(mysql_query("SELECT MAX(sbill_no) as sbill_no FROM reverse_bills"),0))+1;
					 }
					 else if ($dist_type == 'dash'){
					 	$dbill_no = (mysql_result(mysql_query("SELECT MAX(dbill_no) as dbill_no FROM reverse_bills"),0))+1;
					 } 
				}
			//insert bill items in details bill table
				$q_bd = "INSERT INTO reverse_bill_details(series_name,item_name,no_boxes_ordered,no_items_ordered,orig_item_amt,box_item_amt,bill_no) VALUES ('$cat','$item','$box_quantity','$item_quantity','$orignal_amount_total','$per_item_amount',$bill_no)";
				mysql_query($q_bd); 
		}
		if($total_amount > 0){
		date_default_timezone_set('Asia/Karachi');
		$today = date("Y-m-d H:i:s");
		if ($sbill_no != '0' && !empty($sbill_no))
			$q_db = "INSERT INTO reverse_bills (bill_no,distributor_name,customer_name,total_amount,date_bill,sbill_no) VALUES ($bill_no,'$Distributor','$customer',$total_amount,'$today','$sbill_no')";
		else if ($dbill_no != '0' && !empty($dbill_no))
			$q_db = "INSERT INTO reverse_bills (bill_no,distributor_name,customer_name,total_amount,date_bill,dbill_no) VALUES ($bill_no,'$Distributor','$customer',$total_amount,'$today','$dbill_no')";			
		mysql_query($q_db);
			/* insert for total bill payment method */
			$q3="SELECT id_distributor,Name_distributor FROM distributors_list where Name_distributor='$Distributor'";
			$result = mysql_query($q3);
				while($row1=mysql_fetch_array($result))
					$dist_id = $row1['id_distributor'];
					
			$id =0;
			$total_unpaid_amount = 0;
			$add_amount = $total_amount;
			
			$q13="SELECT * FROM payments_history where distributor_id=$dist_id";
			$result1 = mysql_query($q13);
			$num_rows = mysql_num_rows($result1);
			if($num_rows > 0){
				while($row=mysql_fetch_array($result1)){
					$total_unpaid_amount = $row['total_unpaid_amount'];
					$id = $row['id'];
				}
				$new_amount = $total_unpaid_amount - $add_amount;
				$qu="UPDATE payments_history SET total_unpaid_amount=$new_amount where id=$id";
				mysql_query($qu);
			}
			mysql_query("INSERT INTO payment_dates (dist_id, amount_paid, pay_date,check_no) VALUES ('$dist_id','$total_amount','$today','Bill Reversed')");	
		}
		/* ------------- */
		
	}
	
	header("Location: distributors.php?tab=distributors");
?>	
