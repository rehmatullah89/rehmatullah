<?php
error_reporting(0);			
include("header2.php");
?>
<section id="content">
		<div class="container">
			<div class="inside bot-indent">
				
				<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
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
	
	if(isset($_POST)){
	// echo "<pre>";print_r($_POST);
		$j=1;
		$arr = array();
		$arr2 = array();
		foreach ($_POST as $key=>$value){
		
			if($key == 'Distributor' )
			$Distributor = $value;
			if($key == 'customers' )
			$customer = $value;
			
			 if($value == 0 || $value=='submit')
				echo "";
			else
			{ 
				// echo $key."&".$value;
				$cat_item = explode("^", $key);
				$arr[$j] = $cat_item;
				$arr2[$j] = $value;
				$j++;
			}
		}
		// echo "<pre>";print_r($arr);
?>
<label><b>Distributor:</b></label><?php echo $Distributor;?> &nbsp&nbsp&nbsp&nbsp&nbsp <label><b>Customer:</b></label><?php echo $customer;?>
</br>
<table class="imagetable" width="980" align="center">
<tr>
	<th>Serial#</th><th>Model Name(Series)</th><th>No. of Boxes</th><th>Amount</th>
</tr>
<?php
$l=0;
$cat = 0;
$item= 0;
$total_amount = 0;
		foreach($arr as $arr_vals){
			$k=0;
			$l++;
			foreach($arr_vals as $key1=>$val1){
				if($k==0)
				$cat = $val1;
				 if($k == 1){
					$item= $val1;
					}
				$k++;
				
			}
			// echo "cat:".$cat."</br>";
			// echo "item:".$item."</br>";
			// echo "Arr:".$arr2[$l];
			// die();
			$q3="SELECT * FROM products where REPLACE(series_name,' ', '_')='$cat' and REPLACE(item_name,' ', '_')='$item'";
			$result = mysql_query($q3);
			while($row=mysql_fetch_array($result)){
			
				/* get distributir id */
				$qq1 = "SELECT id_distributor from distributors_list where Name_distributor='$Distributor'";
				$rs1 = mysql_query($qq1);
				while($ro1=mysql_fetch_array($rs1)){
				$dist_id = $ro1['id_distributor'];
				}
				/* calc discount on each item */
				$qq2="SELECT discount FROM discounts_distributors where REPLACE(series_name,' ', '_')='$cat' and REPLACE(item_name,' ', '_')='$item' and distributor_id=$dist_id";
				$rs2 = mysql_query($qq2);
				while($ro2=mysql_fetch_array($rs2)){
				$discount = $ro2['discount'];
				}
				/* ------- */
			
			$per_box_items = $row['per_box_items']; //no of items in a box
			$unit_price = $row['unit_price']; //unit price of item
			$cotton_quantity = $row['cotton_quantity']; //no of cottons available
			$cotton_boxses = $row['cotton_boxses']; //no of boxes in cotton
			$remaining_box_qty = $row['remaining_box_qty']; //boxes without cotton/in open cotton
			$item_val_arr = $arr2[$l]; //number of items(boxes) ordered
			$total_amount = $total_amount + ($item_val_arr*$per_box_items*$unit_price)*(1- $discount/100);
			?>
			<tr><td><? echo $l;?></td><td><?php echo $row['item_name']."(".$cat.")";?></td><td><?php echo $item_val_arr."(boxes)*".$per_box_items."(qty)=".$item_val_arr*$per_box_items;?> </td><td><?php echo $item_val_arr."*".$per_box_items."*".$unit_price."- disc =".($item_val_arr*$per_box_items*$unit_price)*(1- $discount/100);?> </td></tr>
			
			<?php
			/* update inventory */
			$toatl_boxes_available = $cotton_quantity*$cotton_boxses + $remaining_box_qty;
			$toatl_remaining_boxes = $toatl_boxes_available - $item_val_arr;
			
			$quotient_cottonts = intval($toatl_remaining_boxes / $cotton_boxses);
			$remainder_boxes = $toatl_remaining_boxes % $cotton_boxses;
			
			$qu="UPDATE products SET cotton_quantity='$quotient_cottonts' ,remaining_box_qty='$remainder_boxes' where REPLACE(series_name,' ', '_')='$cat' and REPLACE(item_name,' ', '_')='$item'";
			mysql_query($qu);
			/* --------- */
			}
		}
		echo "<tr><td style='color:red;'>Total Amount</td><td></td><td></td><td>".$total_amount."</td></tr>";
	}
?>	

	</table></br></br>

					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>	