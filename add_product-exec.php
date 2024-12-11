<?php
	//Include database connection details
	require_once('config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
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
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
		// ****************
		$uploadDir = 'upload/'; //Image Upload Folder
		$fileName = $_FILES['Photo']['name'];
		$tmpName  = $_FILES['Photo']['tmp_name'];
		$fileSize = $_FILES['Photo']['size'];
		$fileType = $_FILES['Photo']['type'];

		$filePath = $uploadDir . $fileName;

		$result = move_uploaded_file($tmpName, $filePath);
		if (!$result) {
		echo "Error uploading file";
		exit;
		}

		if(!get_magic_quotes_gpc())
		{
			$fileName = addslashes($fileName);
			$filePath = addslashes($filePath);
		}

		//$query = "INSERT INTO $db_table ( name,image ) VALUES ('name','$filePath')";

	//***********************
	//Sanitize the POST values
	$series_name = clean($_POST['series_name']);
	$product_name = clean($_POST['product_name']);
	$product_price = clean($_POST['product_price']);
	$cotton_quantity = clean($_POST['cotton_quantity']);
	$cotton_boxses = clean($_POST['cotton_boxses']);
	$per_box_items = clean($_POST['per_box_items']);
	
	if($series_name == '') {
		$errmsg_arr[] = 'Series Name missing';
		$errflag = true;
	}
	if($product_name == '') {
		$errmsg_arr[] = 'Product name missing';
		$errflag = true;
	}
	if($product_price == '') {
		$errmsg_arr[] = 'Product price missing';
		$errflag = true;
	}
	if($cotton_quantity == '') {
		$errmsg_arr[] = 'cotton quantity missing';
		$errflag = true;
	}
		if($cotton_quantity == '') {
		$errmsg_arr[] = 'cotton quantity missing';
		$errflag = true;
	}
		if($cotton_boxses == '') {
		$errmsg_arr[] = 'cotton boxses missing';
		$errflag = true;
	}
		if($filePath == '') {
		$errmsg_arr[] = 'image path missing';
		$errflag = true;
	}
	
	//Check for duplicate image
	if($filePath != '') {
		$qry = "SELECT * FROM products WHERE item_image='$filePath'";
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				$errmsg_arr[] = 'Image file already exists!';
				$errflag = true;
			}
			@mysql_free_result($result);
		}
	}

	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo "<li style='color:red;'>",$msg,'</li>'; 
		}
		echo '</ul></br>';
		echo "<a href='add_product.php'><-Go back and resolve error!</a>";
		die();
		session_write_close();
		header("location: error_page.php");
		exit();
	}
	


	//Create INSERT query
	$qry = "INSERT INTO products(item_name, unit_price, cotton_quantity, cotton_boxses, per_box_items, item_image, series_name) VALUES('$product_name' ,'$product_price','$cotton_quantity','$cotton_boxses','$per_box_items','$filePath','$series_name')";
	$result = @mysql_query($qry);

	//Check whether the query was successful or not
	if($result) {
	$_SESSION['register'] = "Item successfully added!";	
		header("location: products.php?tab=products");
		exit();
	}else {
		die("Query failed");
	}
?>