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
			
			$q="SELECT id_distributor FROM distributors_list where Name_distributor='$mainOption'";
			$result = mysql_query($q);
			
			while($row=mysql_fetch_array($result)){ 
			$dist_id = $row['id_distributor'];
			}
			
			$q1="SELECT customer_name FROM customers where distributor_id = $dist_id ";
			$result1 = mysql_query($q1);
			echo "<option></option>";
			while($row1=mysql_fetch_array($result1)){ 
			 echo "<option>".$row1['customer_name']."</option>";
			}
			
?>