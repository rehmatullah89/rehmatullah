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
	
			$mainOption = $_POST['option'];
			
			if(!empty($mainOption)){
				$q="SELECT item_id,item_name FROM products where series_name='$mainOption'";
				$result = mysql_query($q);
				$c_rows = mysql_num_rows($result);
				$div1 = $c_rows/2;
				$div2 = $c_rows - $div1;
				$array = array();
				while($row1=mysql_fetch_array($result)){ 
					$array[$row1['item_id']] = $row1['item_name'];
				}
				echo '<table class="imagetable" width="920" align="center"><tr><th>Item Name</th><th>Cotton Quantity</th><th>Box Quantity</th><th>Update Price/(Unit)</th></tr>';
				foreach($array as $key => $val)
					echo '<tr><td>'.$val.'</td><td><input type="text" name="cotton_'.$key.'"></td><td><input type="text" name="box_'.$key.'"></td><td><input type="text" name="price_'.$key.'"></td></tr>';
				echo '</table>';
				
			}
			
?>