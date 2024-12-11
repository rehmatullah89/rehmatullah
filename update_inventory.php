<?php
error_reporting(0);
include("header.php");
?>
<script>
$(document).ready(function () {
    $('#series_id').change(function(){
        $.ajax({
            url: "get_series_products.php",
            type: "post",
            data: {option: $(this).find("option:selected").val()},
            success: function(data){
                //adds the echoed response to our container
                $("#create_table_products").html(data);
            }
        });
    });
 
});
</script>

	<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
			
					<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2>Update Selected Inventory!</h2>
				
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
	
	if(isset($_POST['submit'])){
		foreach($_POST as $key=>$value){
			if($value != '' &&  $value != 'Update Inventory'){
				if (strpos($key,'cotton_') !== false){
					$id = explode('_', $key);
					if($value>0){
						$cottnQntity = mysql_result(mysql_query("SELECT cotton_quantity from products where item_id=".$id[1].""),0);
						$T_cotton = $cottnQntity+$value;
						mysql_query("UPDATE products SET cotton_quantity=".$T_cotton." where item_id=".$id[1]."");	
					}
				}
				if (strpos($key,'box_') !== false){
					$id = explode('_', $key);
					if($value>0){
						$BoxQntity = mysql_result(mysql_query("SELECT remaining_box_qty from products where item_id=".$id[1].""),0);
						$T_box = $BoxQntity+$value;
						mysql_query("UPDATE products SET remaining_box_qty=".$T_box." where item_id=".$id[1]."");
					}
				}
				if (strpos($key,'price_') !== false){
					$id = explode('_', $key);
					if($value>0){
						mysql_query("UPDATE products SET unit_price=".$value." where item_id=".$id[1]."");
					}
				}
			}
		}
		echo '<script>alert("Inventory Updated Successfully!")</script>';
	}
		
?>
				<form action="" method="post">
<div class="show_dist">
<label>Series Name:&nbsp</label><select name="series_id" id="series_id"><option>Select Series</option>
	<?php
		$sid = '';
		$qry = "SELECT * from series";
		$series=mysql_query($qry);
		while($row2=mysql_fetch_array($series)){
			$sid = $row2['series_id'];
			echo "<option>".$row2['series_name']."</option>";
		}
		?>
</select>
</div>
<div id="create_table_products"></div>
</br>
</br>
<input type="submit" name="submit" onclick="return confirm('Are u sure? You want to Update Inventory?');" value="Update Inventory">
</form>
		
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>