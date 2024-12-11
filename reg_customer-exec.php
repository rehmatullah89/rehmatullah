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
		// print_r($_POST);
		// die("bye");
	//Sanitize the POST values
	$name = clean($_POST['name']);
	$phone = clean($_POST['tel']);
	$address = clean($_POST['address']);
	
	if($name == '') {
		$errmsg_arr[] = 'Name missing';
		$errflag = true;
	}
	if($phone == '') {
		$errmsg_arr[] = 'Phone# missing';
		$errflag = true;
	}
	if($address == '') {
		$errmsg_arr[] = 'Address missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the registration form
	$id = $_REQUEST['id'];
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: add_customers.php?id=$id");
		exit();
	}
	


	//Create INSERT query
	$qry = "INSERT INTO customers(customer_name, customer_phone, customer_address, distributor_id) VALUES('$name','$phone','$address',$id)";
	$result = @mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
	$_SESSION['register_message'] = 'Customer successfully added!';	
		header("location: edit_distributor.php?id=$id");
		exit();
	}else {
		die("Query failed");
	}
?>