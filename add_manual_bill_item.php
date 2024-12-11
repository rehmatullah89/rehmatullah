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
			$total_amount =0;
			$orignal_total_amount =0;
			$item_box_chk_pt=0;//check point for items and boxes
			$total_boxes_c =0;
			$cat = "";
			$item = "";
			$key = $_REQUEST['id'];//cateogry and item names
			$item_qty = $_REQUEST['value'];//items quantity
			// $Distributor = $_REQUEST['dist'];//distributor name
			$Customer = $_REQUEST['cstmr'];//Customer name
			$Customer_phone = $_REQUEST['phone'];//Customer phone
			$Customer_address = $_REQUEST['address'];//Customer address
			//$Customer_discount = json_decode($_REQUEST['discount']);//item discount_SeriesName
			$Customer_discount = $_REQUEST['discount'];//item discount_SeriesName
            $Customer_discount=explode(",",$Customer_discount);
         	
             // print_r($Customer_discount);
			// echo "size=".sizeof($Customer_discount);
		   // echo '<script type="text/javascript"> alert($Customer_discount); </script>';
			if($Customer == "" || $Customer_phone == ""){
			echo '<script type="text/javascript"> alert("Please Enter Customer Name/Phone first!"); </script>';
			exit();
			}
			//*******for boxes*******
			if (strstr($key, '^'))
			{
				$cat_item = explode("^", $key);
				$cat = $cat_item[0];//category name
				$item = $cat_item[1];//item name
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
			//////////////
			echo '<table class="imagetable" align="center">
			<tr><th>SN#</th><th>Model Name</th><th>Series Name</th><th>Boxes/Items</th><th>Total Amount</th><th>Net Amount</th></tr>';
			
			$q3="SELECT * FROM products where series_name='$cat' and item_name='$item'";
			$result = mysql_query($q3);
			while($row=mysql_fetch_array($result)){
			
				
				/* Calc discount on each item */
				foreach ($Customer_discount as $custm_disc){
				$discount_seriesName = explode("~", $custm_disc);
				$discount = $discount_seriesName[0];//discount
				$seriesName = $discount_seriesName[1];//series Name
				if($seriesName == $cat)
					break;
				}
				/* ------- */
				// echo $discount."__".$seriesName;die();
			
			$per_box_items = $row['per_box_items']; //no of items in a box
			$unit_price = $row['unit_price']; //unit price of item
			$cotton_quantity = $row['cotton_quantity']; //no of cottons available
			$cotton_boxses = $row['cotton_boxses']; //no of boxes in cotton
			$remaining_box_qty = $row['remaining_box_qty']; //boxes without cotton/in open cotton
			$remaining_item_qty = $row['remaining_item_qty']; //items without boxes/in open box
			$item_val_arr = $item_qty; //number of items(boxes) ordered
			if($item_box_chk_pt == 1){
			$orig_item_amount = $item_val_arr*$unit_price;
			$disc_item_amount = ($item_val_arr*$unit_price)*(1- $discount/100);
			}
			else{
			$orig_item_amount = $item_val_arr*$per_box_items*$unit_price;
			$disc_item_amount = ($item_val_arr*$per_box_items*$unit_price)*(1- $discount/100);
			}
			// $total_amount = $total_amount + $disc_item_amount;
			
			
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
								if($item_box_chk_pt == 1){//if it's item or box ordered & (now it is item)
								
									if($item_n[6] == 0)//if previous was box or item
									{//previous was box now item
									$orig_item_amount1 = $item_n[2]*$per_box_items*$unit_price;
									$disc_item_amount1 = ($item_n[2]*$per_box_items*$unit_price)*(1- $discount/100);
									
									$orig_item_amount2 = $item_val_arr*$unit_price;
									$disc_item_amount2 = ($item_val_arr*$unit_price)*(1- $discount/100);
									
									$orig_item_amount = $orig_item_amount1 + $orig_item_amount2;
									$disc_item_amount = $disc_item_amount1 + $disc_item_amount2;
									$item_val_arr = $item_n[2] + $item_qty/10;
									}
									else{
										$item_val_arr = $item_n[2] + $item_qty;
										$disc_item_amount = ($item_val_arr*$unit_price)*(1- $discount/100);
										$orig_item_amount = $item_val_arr*$unit_price;
										}
								}
								else{ //(Now it is box ordered)
									if($item_n[6] == 1)//if previous was box or item
									{//previous was item now box
									$orig_item_amount1 = $item_n[2]*$unit_price;
									$disc_item_amount1 = ($item_n[2]*$unit_price)*(1- $discount/100);
									
									$orig_item_amount2 = $item_val_arr*$per_box_items*$unit_price;
									$disc_item_amount2 = ($item_val_arr*$per_box_items*$unit_price)*(1- $discount/100);
									
									$orig_item_amount = $orig_item_amount1 + $orig_item_amount2;
									$disc_item_amount = $disc_item_amount1 + $disc_item_amount2;
										$item_val_arr = $item_n[2]/10 + $item_qty;
										
									}
									else{
									$item_val_arr = $item_n[2] + $item_qty;
									$orig_item_amount = $item_val_arr*$per_box_items*$unit_price;
									$disc_item_amount = ($item_val_arr*$per_box_items*$unit_price)*(1- $discount/100);
									}
								}
								
								$arr = array($item,$cat,$item_val_arr,$orig_item_amount,$disc_item_amount,$per_box_items,$item_box_chk_pt);
								$_SESSION['cart'][$k] = $arr;
								$flag=1;
								}
						
						}
			}
			
			// if (!strstr($item_qty, '.') && $item_box_chk_pt==1)
			// $item_qty = $item_qty/10;
			
			/* insert into array */
			//item_name, series_name, no_of_boxes, type1_boxe_amount
			if($flag == 0){
				$arr = array($item,$cat,$item_qty,$orig_item_amount,$disc_item_amount,$per_box_items,$item_box_chk_pt);
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
								$item_box_chk_pt = $item_x[6];
				if($i==2){
					if($item_box_chk_pt == 1){
						if(strstr($item_x[$i], '.')){
							$total_boxes_c = $total_boxes_c + $item_x[$i];
							echo "<td>".$item_x[$i]."(items)=".$item_x[$i]."</td>";}
						else{						
							$total_boxes_c = $total_boxes_c + $item_x[$i]/10;
							echo "<td>".$item_x[$i]."(items)=".$item_x[$i]."</td>";}
					}
					else{
					$total_boxes_c = $total_boxes_c + $item_x[$i];
					echo "<td>".$item_x[$i]."x".$per_box_items."(items)=".$item_x[$i]*$per_box_items."</td>";}
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
			
			
	}
			
			echo "</table>";
?>
<input type='submit'	name='submit' value='submit'>											
