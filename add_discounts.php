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

	if (isset($_POST['submit'])) {
		$distributor = '';
		foreach ($_POST as $key => $value) {
			if ($key == 'distributor')
				$distributor = $value;	
			if ($key != 'submit' && $key != 'distributor' && !empty($value)) {
				$series_name =  trim(str_replace('_', ' ', $key));
				mysql_query("delete from discounts_distributors where series_name='$series_name' and distributor_id='$distributor'");
				$r2=mysql_query("SELECT item_id , item_name, series_name from products where series_name='$series_name'");
				while ($row = mysql_fetch_array($r2)) {
					$item_name = $row['item_name'];
					mysql_query("INSERT INTO discounts_distributors (series_name, item_name, discount, distributor_id) VALUES ('$series_name','$item_name','$value','$distributor')");
				}
			}
		}
		echo "<script>alert('Discount added Successfully!')</script>";
	}
?>

<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
			
				<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2 class="extra">Add discounts for Distributor: <i style="color:red;"><?php  echo @mysql_result(mysql_query("Select Name_distributor from distributors_list where id_distributor='".$_REQUEST['id']."'"),0,0); ?></i> here!</h2>
							
											</br>
											<form action="" method="post">
											<input type="hidden" name="distributor" value="<?php echo $_REQUEST['id'];?>">
<table class="imagetable" width="980" align="center">
<tr>
	<th>Serial#</th><th>Series Name</th><th>Add %age Discount</th><th>Actions</th><!--<th> Pices(Extra)</th> -->
</tr>
	<?php 	
	$i=1;
	$dist_id = $_REQUEST['id'];

$q="SELECT * FROM series";
$series_names=mysql_query($q);

while($row1=mysql_fetch_array($series_names)){ 
	$ser_id= $row1['series_id'];?>
<tr style="text-align:center;"><td><? echo $i;?></td><td><?php echo trim($row1['series_name']);?></td><td><input type="number" step="0.1" name="<?php echo trim($row1['series_name']);?>" id="<?php echo $ser_id;?>" value=""></td><td><a href="edit_discount.php?dist_id=<?php echo $dist_id;?>&ser_id=<?php echo $ser_id;?>&sname=<?php echo trim($row1['series_name']);?>">View/Edit Discount</a></td></tr>
	<?php
	$i++;
	}
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