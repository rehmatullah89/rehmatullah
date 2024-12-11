<?php
error_reporting(0);
include("header2.php");
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
	$distributor_id = $_REQUEST['dist_id'];
	$series_id = $_REQUEST['ser_id'];
	$series_name = $_REQUEST['sname'];

	if (isset($_POST['submit'])) {
		foreach ($_POST as $key => $value) {
			if ($key != 'submit') {
				if (strpos($key,'discount') !== false){
        			
        				$id = explode('_', $key);
        				$oid = $id[1];
        				if (empty($_POST["distprice_$oid"]))
        					$new_price = 0;
        				else
        					$new_price = $_POST["distprice_$oid"];
        				if (empty($_POST["oldamount_$oid"]))
        					$old_amount = 0;
        				else
        					$old_amount = $_POST["oldamount_$oid"];
						mysql_query("update discounts_distributors set discount='$value', dist_item_price='$new_price',old_list_price='$old_amount' where disc_id='".$id[1]."'");		
        			
        		}
			}
		}
		//window.location = 'add_discounts.php?id=$distributor_id';
		echo "<script>window.location = 'add_discounts.php?id=$distributor_id';</script>";
		//header("location: add_discounts.php?id=$distributor_id");
	}
?>

<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
			
				<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2 class="extra">View / Edit discounts for Distributor: <i style="color:red;"><?php  echo @mysql_result(mysql_query("Select Name_distributor from distributors_list where id_distributor='".$_REQUEST['dist_id']."'"),0,0); ?></i> here!</h2>
							
											</br>
											<form action="" method="post">
<h3>For series: <i style="color:green"><?php echo $series_name;?></i></h3>
<table class="imagetable" width="980" align="center">
<tr>
	<th>Serial#</th><th>Product Name</th><th>Edit %age Discount</th><th>Edit Old List Price</th><th>Special Price</th>
</tr>
	<?php 	
	$i=1;
$query = mysql_query("SELECT * FROM discounts_distributors where distributor_id= '$distributor_id' and series_name='$series_name'");
while($row1=mysql_fetch_array($query)){ ?>
<tr style="text-align:center;"><td><? echo $i++;?></td><td><?php echo trim($row1['item_name']);?></td><td><input type="number" step="0.1" name="discount_<?php echo $row1['disc_id'];?>" value="<?php echo $row1['discount'];?>"></td><td><input type="number" name="oldamount_<?php echo $row1['disc_id'];?>" value="<?php echo $row1['old_list_price'];?>"></td><td><input type="number" name="distprice_<?php echo $row1['disc_id'];?>" value="<?php echo $row1['dist_item_price'];?>"></td></tr>
	<?php	}
?>
</table><br/>
<br/><br/>
		
<input	type="submit" name="submit" value="submit">						
</form>												
											
				
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
include("footer.php");
?>		