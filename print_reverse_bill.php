<?php
session_start();
error_reporting(0);			
include("header2.php");
?>
<script>
function printDiv(divName) {
	var bill_id = '<?php echo $_REQUEST['b_id']; ?>';
	$.ajax({
            url: "changeButtonColor.php?id="+bill_id,
			type: 'POST',
			success: function(data){
				//
            }
    });
		
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
	
		$bill_no = $_REQUEST['b_id'];
		$today = date("F j, Y, g:i a");
		$new_bill_no = 0;
		// die();
		
		$qry="SELECT * from reverse_bills where bill_no='$bill_no'";
		$res = mysql_query($qry);
		
		while($row = mysql_fetch_array($res)){
			$distributor_name = $row['distributor_name'];
			$customer_name = $row['customer_name'];
			$total_amount = $row['total_amount'];
			$amount_paid = $row['amount_paid'];
			$date_bill = $row['date_bill'];
			if($row['sbill_no'] != 0)
				$new_bill_no = $row['sbill_no'];
			else if($row['dbill_no'] != 0)
				$new_bill_no = $row['dbill_no'];
		}
			
		$result_serie_type = @mysql_result(mysql_query("Select distributor_for from distributors_list where Name_distributor='".$distributor_name."'"),0,0); 
		if(empty($result_serie_type) || $result_serie_type == 'sanva'){
		   echo "<h1 align='center'><img src='images/sanva_logo.jpg'></h1>";
		   //echo "<h1 align='center' style='font-size: 6em; color:black !important;'>SANVA</h1><center><h4 style='color:red;'>Electric Company</h4></center>"; 
		}
		else if ($result_serie_type == 'dash'){
			echo "<h1 align='center'><img src='images/dash_logo.jpg'></h1>";
			//echo "<h1 align='center' style='font-size: 6em; color:black !important;'>DASH</h1><center><h4 style='color:red;'>Electric Company</h4></center>";
		}
		
		echo "<table><tr><td style='padding-left:5px;'>Bill Date : <span style='color:red;'>$date_bill</span><br/>Current Date : $today</td><td align='right' style='width:75%; color:red;'><b>Bill No : $new_bill_no</b></td>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td style='font-size:24px; color:red; padding:5px;' align='right'>Returned Bill</td><td></td></tr>
		<tr><td style='font-size:24px; padding:5px;' align='right'>Distributor Name:</td><td style='font-size:22px; padding:5px; color:red; width:75%;' align='left'>$distributor_name</td></tr>
		</table>";

?>
</br>
<table class="imagetable" width="980" align="center">
			<tr><th>SN#</th><th>Series Name</th><th>Pro. Name</th><th>Boxes</th><th>Items</th><th>U.Price</th><th>Total Amount</th><th>Net Amount</th></tr>
<?php
$i=1;
$orig_total_amount =0;
$total_qty =0;
$total_item_count=0;
$qry1="SELECT * from reverse_bill_details where bill_no='$bill_no'";
		$res1 = mysql_query($qry1);
		
		while($row1 = mysql_fetch_array($res1)){
			$item_name = $row1['item_name'];
			$series_name = $row1['series_name'];
			$no_boxes_ordered = trim($row1['no_boxes_ordered']);
			$no_items_ordered = trim($row1['no_items_ordered']);
			$box_item_amt = $row1['box_item_amt']; //per item total items in box amount(net amount of box ordered)
			$orig_box_item_amt = $row1['orig_item_amt']; //(Total orig amount of boxes ordered)
			
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
			
			echo "<tr><td>$i</td><td>$series_name</td><td>$item_name</td><td>".$no_boxes_ordered."+".$div_res_item."(boxes)</td><td>".$res_boxes."</td><td>".($orig_box_item_amt==0?$unit_price:$orig_box_item_amt/$res_boxes)."</td><td>".($orig_box_item_amt==0?$amt_t:$orig_box_item_amt)."</td><td>$box_item_amt</td></tr>";
			$i++;
			
			$no_boxes_ordered = 0;
			$no_items_ordered = 0;
		}

?>
<tr><th style='font-size:16px;'>Total</th><td></td><td></td><th  style='color:red; font-size:16px;'><?php echo $total_qty; ?></th><th  style='font-size:16px;'><?php echo $total_item_count;?></th><td></td><th style='font-size:16px;'><?php echo $orig_total_amount;?></th><th style='color:red; font-size:16px;'><?php echo $total_amount;?></th></tr>

	</table></br></br>
	<?php echo "<table style='font-size:200%; color='purple';'><tr><td>Total Amount:</td><td>$total_amount</td></tr><tr><td>&nbsp</td><td>&nbsp</td></tr></table>";?></br>
	</div><input type="button" onclick="printDiv('printableArea')" value="print Bill!" />
					</br></br>
					
					
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
    Cufon.replace('h1', { color: black });
</script>
<?php
include("footer.php");
?>	
