<?php
session_start();
error_reporting(0);			
include("header2.php");
?>
<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
<section id="content">
		<div class="container">
		<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
			<div class="inside bot-indent" id="printableArea">
				
				
<?php	//Include database connection details
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
		
		date_default_timezone_set('Asia/Karachi');
		$bill_no = $_REQUEST['b_id'];
		$today = date("F j, Y, g:i a");  
		// die();
		
		$qry="SELECT * from customers_bills where bill_no='$bill_no'";
		$res = mysql_query($qry);
		
		while($row = mysql_fetch_array($res)){
			// $distributor_name = $row['distributor_name'];
			$customer_name = $row['customer_name'];
			$customer_phone = $row['customer_phone'];
			$customer_address = $row['customer_address'];
			$total_amount = $row['total_amount'];
			$amount_paid = $row['amount_paid'];
			$date_bill = $row['date_bill'];
		}
		echo "</br></br></br></br></br></br></br>";
		echo "Bill Date : $date_bill</br>Current Date : $today</br><font style='color:red;'>Bill No : $bill_no</font></br></br></br><table style='font-size:200%; color='purple';'><tr><td>&nbsp&nbsp&nbsp</td></tr><tr><td>&nbsp</td><td>&nbsp</td></tr><tr><td>Customer Name:</td><td>$customer_name</td><td>&nbsp&nbsp&nbsp</td></tr><tr><td>&nbsp&nbsp&nbsp&nbsp&nbsp</td></tr><tr><td>Customer Phone:</td><td>$customer_phone</td><td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td><td>Customer Address:</td><td>$customer_address</td><td></tr></table>";

?>
</br>
<table class="imagetable" width="980" align="center">
			<tr><th>SN#</th><th>Series Name</th><th>Pro. Name</th><th>Boxes</th><th>Items</th><th>U.Price</th><th>Total Amount</th><th>Net Amount</th></tr>
<?php
$i=1;
$orig_total_amount =0;
$total_qty =0;
$total_item_count=0;
$div_res_item =0;
$res_b =0;
$res_boxes =0;
$qry1="SELECT * from cbill_details where bill_no='$bill_no'";
		$res1 = mysql_query($qry1);
		
		while($row1 = mysql_fetch_array($res1)){
			$item_name = $row1['item_name'];
			$series_name = $row1['series_name'];
			$no_boxes_ordered = trim($row1['no_boxes_ordered']);
			$no_items_ordered = trim($row1['no_items_ordered']);
			$box_item_amt = $row1['box_item_amt']; //per item total items in box amount(net amount of box ordered)
			
			
			$qry2="SELECT item_name,series_name,unit_price,per_box_items from products where item_name='$item_name' and series_name='$series_name'";
			
			$res2 = mysql_query($qry2);
			while($row2 = mysql_fetch_array($res2)){
				$unit_price = $row2['unit_price'];
				$per_box_items = $row2['per_box_items'];
			}
			
			if($no_boxes_ordered != "" || $no_boxes_ordered != 0)
			$orig_total_amount = $orig_total_amount + ($no_boxes_ordered*$per_box_items*$unit_price);
			
			if($no_items_ordered != "" || $no_items_ordered != 0){
			$orig_total_amount = $orig_total_amount + ($no_items_ordered*$unit_price);
			$total_qty = $total_qty + $no_boxes_ordered + $no_items_ordered/10;
			}else
			$total_qty = $total_qty + $no_boxes_ordered; 
			
			if($no_boxes_ordered != "" || $no_boxes_ordered != 0)
			$total_item_count = $total_item_count + ($no_boxes_ordered*$per_box_items);
			
			if($no_items_ordered != "" || $no_items_ordered != 0){
			$total_item_count = $total_item_count + $no_items_ordered;
			$div_res_item = $no_items_ordered/10;
			}else
			$div_res_item =0;
			
			$res_boxes = $no_boxes_ordered*$per_box_items + $no_items_ordered;
			$amt_t = ($no_boxes_ordered*$per_box_items*$unit_price) + $no_items_ordered*$unit_price;
			
			echo "<tr><td>$i</td><td>$series_name</td><td>$item_name</td><td>".$no_boxes_ordered."+".$div_res_item."(boxes)</td><td>".$res_boxes."</td><td>".$unit_price ."</td><td>".$amt_t."</td><td>$box_item_amt</td></tr>";
			$i++;
			
			$no_boxes_ordered = 0;
			$no_items_ordered = 0;
		}

?>
<tr><th style='color:red;'>Total</th><td></td><td></td><th><?php echo $total_qty; ?></th><th><?php echo $total_item_count;?></th><td></td><th><?php echo $orig_total_amount;?></th><th><?php echo $total_amount;?></th></tr>

	</table></br></br>
	<?php echo "<table style='font-size:200%; color='purple';'><tr><td>Total Amount:</td><td>$total_amount</td></tr><tr><td>&nbsp</td><td>&nbsp</td></tr><tr><td>Amount Paid:</td><td style='color:green;'>$amount_paid</td><td>Balance Remaning:</td><td style='color:red;'>".($total_amount-$amount_paid)."</td></tr></table>";?></br>
	<input type="button" onclick="printDiv('printableArea')" value="print Bill!" />
					</div></br></br>
					
					
				</div>
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>	