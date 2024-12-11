<?php
error_reporting(0);
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
$dist_id = '';
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
					$bill_no = mysql_result(mysql_query("SELECT MAX(bill_no) as bill_no FROM distributor_bills"),0)+1;
					$qry = mysql_query("SELECT id_distributor,distributor_for FROM distributors_list where Name_distributor='".$Distributor."'");
					while ($row = mysql_fetch_array($qry)) {
						$dist_type = $row['distributor_for'];
						$dist_id = $row['id_distributor'];
					}
					 if($dist_type == 'sanva'){
					 	$sbill_no = (mysql_result(mysql_query("SELECT MAX(sbill_no) as sbill_no FROM distributor_bills"),0))+1;
					 }
					 else if ($dist_type == 'dash'){
					 	$dbill_no = (mysql_result(mysql_query("SELECT MAX(dbill_no) as dbill_no FROM distributor_bills"),0))+1;
					 } 
				}
			//insert bill items in details bill table
				$q_bd = "INSERT INTO bill_details(series_name,item_name,no_boxes_ordered,no_items_ordered,orig_item_amt,box_item_amt,bill_no) VALUES ('$cat','$item','$box_quantity','$item_quantity','$orignal_amount_total','$per_item_amount',$bill_no)";
				mysql_query($q_bd);
			
			
		}
		if($total_amount > 0){
		$mob_bill_no = '';
		date_default_timezone_set('Asia/Karachi');
		$today = date("Y-m-d H:i:s");
		if ($sbill_no != '0' && !empty($sbill_no)){
			$q_db = "INSERT INTO distributor_bills (bill_no,distributor_name,customer_name,total_amount,date_bill,sbill_no) VALUES ($bill_no,'$Distributor','$customer',$total_amount,'$today','$sbill_no')";
			$mob_bill_no = $sbill_no;
		}
		else if ($dbill_no != '0' && !empty($dbill_no)){
			$q_db = "INSERT INTO distributor_bills (bill_no,distributor_name,customer_name,total_amount,date_bill,dbill_no) VALUES ($bill_no,'$Distributor','$customer',$total_amount,'$today','$dbill_no')";
			$mob_bill_no = $dbill_no;
		}			
		mysql_query($q_db);
			$total_balance = 0;
			$phone_num = @mysql_result(mysql_query("Select phone_distributor from distributors_list where Name_distributor='$Distributor'"),0);
			$bill_amt = @mysql_result(mysql_query("Select sum(db.total_amount) as bill_amount from distributor_bills db where db.distributor_name='$Distributor' group by db.distributor_name"),0);
			$revrse_bil_amt = @mysql_result(mysql_query("Select sum(rb.total_amount) as reverse_amt from reverse_bills rb where rb.distributor_name='$Distributor' group by rb.distributor_name"),0);
			$old_amt_total = @mysql_result(mysql_query("Select sum(total_old_amount) as old_amt from payments_history where distributor_id='$dist_id' group by distributor_id"),0);
			$paid_amt_total = @mysql_result(mysql_query("Select sum(amount_paid) as amt_paid from payment_dates where dist_id='$dist_id' group by dist_id"),0);
			$total_balance = (($bill_amt==''?0:$bill_amt)+($old_amt_total==''?0:$old_amt_total))-(($paid_amt_total==''?0:$paid_amt_total));

			if(!empty($phone_num)){
				if($phone_num[0] == '0')
				 $phone_num = '92'.ltrim(preg_replace('/\s+/','',$phone_num),'0');
				$message = ("Bill No:".$mob_bill_no."\n Customer Name:".$customer."\n A Bill of Amount: Rs.".$total_amount." has been added to your account by Sanvakec.com,\n Balance Amount: Rs.".$total_balance."");
				if(strlen($phone_num)==12)
				 vsms_relay_sendsms("nKrnZhWovmad6ddd327-0e17-487c-86be-a4515d38762dOjIxJS6Hdpv143050",$phone_num, $message, "rehmatullah89");
			}
		}
		/* ------------- */
		
	}
	function vsms_relay_sendsms($ApiKey, $PhoneNumber, $Message, $SenderId){
		$url = "http://vsms.club/api/Relay/SendSms";
		$fields = array(
							'ApiKey' => $ApiKey,
							'PhoneNumber' => $PhoneNumber,
							'Message' => $Message,
							'SenderId' => $SenderId,
							'isUnicode' => true
					);
		$fields_string = json_encode($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($fields_string))                                                                       
		); 
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	header("Location: unpaid_bills.php");
?>