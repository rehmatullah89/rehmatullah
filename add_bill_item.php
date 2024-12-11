 <?php
 error_reporting(0);
 session_start();
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
			$discount =0;
			$per_item_amount = 0;
			$dist_item_price =0;
			$old_list_price =0;
			$total_amount =0;
			$orignal_total_amount =0;
			$total_boxes_c =0;
			$key = $_REQUEST['id'];//cateogry and item names
			$item_qty = $_REQUEST['value'];//items quantity
			$Distributor = $_REQUEST['dist'];//distributor name
			$Customer = $_REQUEST['cstmr'];//Customer name
			
			if($Distributor == "Select Distributor Name" || $Distributor == ""){
			echo '<script type="text/javascript"> alert("You have not Selected Distributor!"); </script>';
			exit();
			}
			
			if (strstr($key, '^'))
			{
				$cat_item = explode("^", $key);
				$cat = $cat_item[0];//category name
				$item = $cat_item[1];//item name
				// exit();
				$tota_boxes_avail =0;
				if($cat != "" && $item != "")
				{
					$qry= "SELECT * from products where item_name='$item' and series_name='$cat'";
					$results = mysql_query($qry);
					while($row1=mysql_fetch_array($results))
					$tota_boxes_avail = ($row1['cotton_quantity']*$row1['cotton_boxses'])+$row1['remaining_box_qty'];
					
					if($tota_boxes_avail < $item_qty){
					echo '<script type="text/javascript"> alert("Item boxes only available in store="+'.$tota_boxes_avail.'); </script>';
					exit();
					}
				
				}
			}
			//*******for items*******
			if (strstr($key, '#'))
			{
				$item_box_chk_pt =1;
				$cat_item = explode("#", $key);
				$cat = $cat_item[0];//category name
				$item = $cat_item[1];//item name
				$tota_item_avail =0;
				if($cat != "" && $item != "")
				{
					$qry= "SELECT * from products where item_name='$item' and series_name='$cat'";
					$results = mysql_query($qry);
					while($row1=mysql_fetch_array($results))
					$tota_item_avail = ((($row1['cotton_quantity']*$row1['cotton_boxses'])+$row1['remaining_box_qty'])*$row1['per_box_items'])+$row1['remaining_item_qty'];
					
					if($tota_item_avail < $item_qty){
					echo '<script type="text/javascript"> alert("Items only available in store="+'.$tota_item_avail.'); </script>';
					exit();
					}
				}
			}
			
			echo '<table class="imagetable" align="center">
			<tr><th>Serial#</th><th>Model Name</th><th>Series Name</th><th>Boxes.Items</th><th>Total Amount</th><th>Net Amount</th></tr>';
			
			$q3="SELECT * FROM products where series_name='$cat' and item_name='$item'";
			$result = mysql_query($q3);
			while($row=mysql_fetch_array($result)){
			
				/* get distributir id */
				$qq1 = "SELECT id_distributor from distributors_list where Name_distributor='$Distributor'";
				$rs1 = mysql_query($qq1);
				while($ro1=mysql_fetch_array($rs1)){
				$dist_id = $ro1['id_distributor'];
				}
				/* calc discount on each item */
				$qq2="SELECT discount,dist_item_price,old_list_price FROM discounts_distributors where series_name='$cat' and item_name='$item' and distributor_id=$dist_id";
				$rs2 = mysql_query($qq2);
				while($ro2=mysql_fetch_array($rs2)){
				$discount = $ro2['discount'];
				$dist_item_price = $ro2['dist_item_price'];
				$old_list_price = $ro2['old_list_price'];
				}
				/* ------- */
			
			$per_box_items = $row['per_box_items']; //no of items in a box
			$unit_price = $row['unit_price']; //unit price of item
			$cotton_quantity = $row['cotton_quantity']; //no of cottons available
			$cotton_boxses = $row['cotton_boxses']; //no of boxes in cotton
			$remaining_box_qty = $row['remaining_box_qty']; //boxes without cotton/in open cotton
			$item_val_arr = $item_qty; //number of items(boxes) ordered
			if($item_box_chk_pt == 1){
				if (!empty($old_list_price) && $old_list_price != 0){
					$per_item_amount = ($item_val_arr*$old_list_price)*(1- $discount/100);
					$orig_item_amount = $item_val_arr*$old_list_price;
				}
				else if (!empty($dist_item_price) && $dist_item_price != 0){
					$per_item_amount = $dist_item_price*($item_val_arr);
					$orig_item_amount = $item_val_arr*$dist_item_price;
				}
				else{
					$per_item_amount = ($item_val_arr*$unit_price)*(1- $discount/100);
					$orig_item_amount = $item_val_arr*$unit_price;
				}
			}
			else{
				if (!empty($old_list_price) && $old_list_price != 0){
					$per_item_amount = ($item_val_arr*$per_box_items*$old_list_price)*(1- $discount/100);
					$orig_item_amount = $item_val_arr*$per_box_items*$old_list_price;
				}
				else if (!empty($dist_item_price) && $dist_item_price != 0){
					$per_item_amount = $dist_item_price*($item_val_arr*$per_box_items);
					$orig_item_amount = ($item_val_arr*$per_box_items)*$dist_item_price;
				}
				else{
					$per_item_amount = ($item_val_arr*$per_box_items*$unit_price)*(1- $discount/100);
					$orig_item_amount = $item_val_arr*$per_box_items*$unit_price;
				}	
			}


			// $total_amount = $total_amount + $per_item_amount;
			
			
			/* update bill items */
			$flag=0;
			if(isset($_SESSION['cart']))
			{
				foreach($_SESSION['cart'] as $k=>$item_n)
						if($item_n[0] == $item && $item_n[1] == $cat){
								if($item_qty == 0 || $item_qty == '')
									unset($_SESSION['cart'][$k]);
								else
								{
									//$item_val_arr = $item_n[2] + $item_qty;
									if($item_box_chk_pt == 1){ //this time item ordered
										if($item_n[6] == 0){
											if (!empty($old_list_price) && $old_list_price != 0){
												$per_item_amount1 = ($item_n[2]*$per_box_items*$old_list_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$per_box_items*$old_list_price;
										
												$per_item_amount2 = ($item_qty*$old_list_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$old_list_price;
											}
											else if ($dist_item_price != 0 && !empty($dist_item_price)){
												$per_item_amount1 = ($item_n[2]*$per_box_items)*$dist_item_price;
												$orig_item_amount1 = ($item_n[2]*$per_box_items)*$dist_item_price;

												$per_item_amount2 = $dist_item_price*($item_qty);
												$orig_item_amount2 = $item_qty*$dist_item_price;
											}
											else{
												$per_item_amount1 = ($item_n[2]*$per_box_items*$unit_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$per_box_items*$unit_price;
											
												$per_item_amount2 = ($item_qty*$unit_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$unit_price;
											}
											$per_item_amount = $per_item_amount1 + $per_item_amount2;
											$orig_item_amount = $orig_item_amount1 + $orig_item_amount2;
											$item_val_arr = $item_n[2] + $item_qty/10;
										}else{
											if (!empty($old_list_price) && $old_list_price != 0){
												$per_item_amount1 = ($item_n[2]*$old_list_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$old_list_price;
										
												$per_item_amount2 = ($item_qty*$old_list_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$old_list_price;
											}
											else if ($dist_item_price != 0 && !empty($dist_item_price)){
												$per_item_amount1 = ($item_n[2])*$dist_item_price;
												$orig_item_amount1 = ($item_n[2])*$dist_item_price;

												$per_item_amount2 = $dist_item_price*($item_qty);
												$orig_item_amount2 = $item_qty*$dist_item_price;
											}
											else{
												$per_item_amount1 = ($item_n[2]*$unit_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$unit_price;
											
												$per_item_amount2 = ($item_qty*$unit_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$unit_price;
											}
											$per_item_amount = $per_item_amount1 + $per_item_amount2;
											$orig_item_amount = $orig_item_amount1 + $orig_item_amount2;
											$item_val_arr = $item_n[2] + $item_qty;

										}
										

									}else{ // now box is being ordered
										if($item_n[6] == 0){ // previous order was also box
											if (!empty($old_list_price) && $old_list_price != 0){
												$per_item_amount1 = ($item_n[2]*$per_box_items*$old_list_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$per_box_items*$old_list_price;
										
												$per_item_amount2 = ($item_qty*$per_box_items*$old_list_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$per_box_items*$old_list_price;
											}
											else if ($dist_item_price != 0 && !empty($dist_item_price)){
												$per_item_amount1 = ($item_n[2]*$per_box_items)*$dist_item_price;
												$orig_item_amount1 = ($item_n[2]*$per_box_items)*$dist_item_price;

												$per_item_amount2 = $dist_item_price*($per_box_items*$item_qty);
												$orig_item_amount2 = $item_qty*$per_box_items*$dist_item_price;
											}
											else{
												$per_item_amount1 = ($item_n[2]*$per_box_items*$unit_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$per_box_items*$unit_price;
											
												$per_item_amount2 = ($item_qty*$per_box_items*$unit_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$per_box_items*$unit_price;
											}
											$per_item_amount = $per_item_amount1 + $per_item_amount2;
											$orig_item_amount = $orig_item_amount1 + $orig_item_amount2;
											$item_val_arr = $item_n[2] + $item_qty;
										}else{ // previous order was item and now it is box ordered
											if (!empty($old_list_price) && $old_list_price != 0){
												$per_item_amount1 = ($item_n[2]*$old_list_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$old_list_price;
										
												$per_item_amount2 = ($item_qty*$per_box_items*$old_list_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$per_box_items*$old_list_price;
											}
											else if ($dist_item_price != 0 && !empty($dist_item_price)){
												$per_item_amount1 = ($item_n[2])*$dist_item_price;
												$orig_item_amount1 = ($item_n[2])*$dist_item_price;

												$per_item_amount2 = $dist_item_price*($per_box_items*$item_qty);
												$orig_item_amount2 = $item_qty*$per_box_items*$dist_item_price;
											}
											else{
												$per_item_amount1 = ($item_n[2]*$unit_price)*(1- $discount/100);
												$orig_item_amount1 = $item_n[2]*$unit_price;
											
												$per_item_amount2 = ($item_qty*$per_box_items*$unit_price)*(1- $discount/100);
												$orig_item_amount2 = $item_qty*$per_box_items*$unit_price;
											}
											$per_item_amount = $per_item_amount1 + $per_item_amount2;
											$orig_item_amount = $orig_item_amount1 + $orig_item_amount2;
											$item_val_arr = $item_n[2]/10 + $item_qty;

										}
									}
									
									$arr = array($item,$cat,$item_val_arr,$orig_item_amount,$per_item_amount,$per_box_items,$item_box_chk_pt);
									$_SESSION['cart'][$k] = $arr;
									$flag=1;
								}
						
						}
			}
			
			
			/* insert into array */
			//item_name, series_name, no_of_boxes, type1_boxe_amount
			if($flag == 0){
				$arr = array($item,$cat,$item_val_arr,$orig_item_amount,$per_item_amount,$per_box_items,$item_box_chk_pt);
				if(isset( $_SESSION['counter']))
				{
				  $_SESSION['counter'] += 1;
				  $_SESSION['cart'][]= $arr;
				}
			   else
				{
				  $_SESSION['counter'] = 1;
				  $_SESSION['cart'] = array();
				  $_SESSION['cart'][]= $arr;	
				}
			}
			// echo "<pre>"; print_r($_SESSION['cart']);
			/* ---------- */
			$count =1;
			foreach($_SESSION['cart'] as $item_x){
			echo "<tr><td>$count</td>";	
			$i=0;
				while($i<5){
					$per_box_items = $item_x[5];
					if($i==2){
					$total_boxes_c = $total_boxes_c + $item_x[$i];
					echo "<td>".$item_x[$i]."(".$per_box_items."/pb)</td>";
					//echo "<td>".$item_x[$i]."</td>";
					}
					else
					echo "<td>".$item_x[$i]."</td>";
					
					if($i==3)
						$orignal_total_amount = $orignal_total_amount + $item_x[$i];

					if($i==4)
						$total_amount = $total_amount + $item_x[$i];
					$i++;
				}
				$count ++;
				echo "</tr>";	
			}
			echo "<tr><th>Total Bill</th><td></td><td></td><td>$total_boxes_c</td><td>$orignal_total_amount</td><th>$total_amount</th></tr>";
			
			// echo "<tr><td>".$_SESSION['counter']."</td><td>";
			// echo $row['item_name']."(".$cat.")";
			// echo "</td><td>".$item_val_arr."(boxes)*".$per_box_items."(qty)=".$item_val_arr*$per_box_items."</td><td>".$item_val_arr."*".$per_box_items."*".$unit_price."- disc =".$per_item_amount."</td></tr>";
			
			// echo "<tr><td></td><td></td><td></td><td></td></tr>";
	
	}
			
			echo "</table>";
?>
<input type='submit'	name='submit' value='submit'>											
<!--	<input type="button" value="Submit Bill?" 
onClick="if(confirm('Are you sure you want to submit this bill?'))
alert('You are very brave!');
else alert('A wise decision!')">	-->									
