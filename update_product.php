<?php
include("header_addForm.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
				
		
				<div class="inside1">
					<div class="wrap">
					
							<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
							<div id="registration" style="width:800px; margin:0 auto;">
 <h2>Update Product</h2>

 <form id="RegisterUserForm" action="" method="post">
 	<fieldset>
         
		<p>
           
            <?php
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
				die("Unable to select database");
			}
			$item_id = $_REQUEST['id'];
			
			if(isset($_POST['UpdateProduct'])){
			$product_price = $_POST['product_price'];
			$cotton_quantity = $_POST['cotton_quantity'];
			$box_quantity = $_POST['box_quantity'];
			$cotton_boxses = $_POST['cotton_boxses'];
			$per_box_items = $_POST['per_box_items'];
			$q1="UPDATE products SET unit_price='$product_price',cotton_quantity='$cotton_quantity',remaining_box_qty='$box_quantity',cotton_boxses='$cotton_boxses',per_box_items='$per_box_items' where item_id='$item_id'";
			mysql_query($q1);
			// header('Location: home.php?tab=home');
			?>
			<script>
			window.location = 'home.php?tab=home';
			</script>
			<?php
			die("END");
			}
			
			$q1="SELECT * FROM products where item_id='$item_id'";
			$r1=mysql_query($q1);
			echo "<label>Series Name:&nbsp&nbsp<label><label>";
			while($row1=mysql_fetch_array($r1)){
			echo $row1['series_name'];		
			echo "</label>";
			?>
	     </p>
		 
		 <p>
            <label>Product Name:</label>&nbsp&nbsp
             <label><?php echo $row1['item_name'];?></label>
         </p>
        
		<p>
            <label>Product Price</label>
            <input id="product_price" name="product_price" type="text" class="text" value="<?php echo $row1['unit_price'];?>" />
         </p>
		
         <p>
            <label>Cotton Quantity</label>
            <input id="cotton_quantity" name="cotton_quantity" type="text" class="text" value="<?php echo $row1['cotton_quantity'];?>" />
         </p>
         <p>
            <label>Box Quantity</label>
            <input id="box_quantity" name="box_quantity" type="text" class="text" value="<?php echo $row1['remaining_box_qty'];?>" />
         </p>
         <p>
            <label>No of Boxes In 1-Cotton</label>
            <input id="cotton_boxses" name="cotton_boxses" type="text" class="text" value="<?php echo $row1['cotton_boxses'];?>" />
         </p>
		 
		 <p>
            <label>No of items in 1-Box</label>
            <input id="per_box_items" name="per_box_items" type="text" class="text" value="<?php echo $row1['per_box_items'];?>" />
         </p>
           
         <p>
            <button id="UpdateProduct" name="UpdateProduct" type="submit">Update Product</button>
         </p>
 	</fieldset>
<?php }?>
 </form>
</div>
					
						<div class="clear"></div>
					</div>
				</div>
						</div>
	</div>
</section>
</div>
<?php
include("footer.php");
?>		
