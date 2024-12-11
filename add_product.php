<?php
include("header_addForm.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
				
		
				<div class="inside1">
					<div class="wrap">
						<?php
						if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo "<li style='color:red;'>",$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
	}
?>
							<br/><br/>
							<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
							<div id="registration" style="width:800px; margin:0 auto;">
 <h2>Add New Product</h2>

 <form id="RegisterUserForm" enctype="multipart/form-data" action="add_product-exec.php" method="post">
 	<fieldset>
         
		<p>
            <label>Select Series(Category)</label>
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
			$q1="SELECT series_name FROM series";
			$r1=mysql_query($q1);
			echo "<select name='series_name'>";
			while($row1=mysql_fetch_array($r1))
			echo "<option name='s_name'>".$row1['series_name']."</option>";		
			echo "</select>";
			?>
	     </p>
		 
		 <p>
        <label>Product Image</label>
        <input type="file" name="Photo" id="Photo" size="2000000" accept="image/gif, image/jpeg, image/x-ms-bmp, image/x-png" size="26">
         </p>
		 
		 <p>
            <label>Product Name</label>
            <input id="product_name" name="product_name" type="text" class="text" value="" />
         </p>
        
		<p>
            <label>Product Price</label>
            <input id="product_price" name="product_price" type="text" class="text" value="" />
         </p>
		
         <p>
            <label>Cotton Quantity</label>
            <input id="cotton_quantity" name="cotton_quantity" type="text" class="text" value="" />
         </p>
        
         <p>
            <label>No of Boxes In 1-Cotton</label>
            <input id="cotton_boxses" name="cotton_boxses" type="text" class="text" value="" />
         </p>
		 
		 <p>
            <label>No of items in 1-Box</label>
            <input id="per_box_items" name="per_box_items" type="text" class="text" value="" />
         </p>
           
         <p>
            <button id="AddProduct" name="AddProduct" type="submit">Add New Product</button>
         </p>
 	</fieldset>

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
