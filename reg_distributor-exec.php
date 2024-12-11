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
		/*print_r($_POST);
		die("bye");*/
	//Sanitize the POST values
	$name = clean($_POST['name']);
	$phone = clean($_POST['tel']);
	$address = clean($_POST['address']);
	$dist_for_series = clean($_POST['dist_for_series']);
	
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
	//Check for duplicate login ID
	if($name != '') {
		$qry = "SELECT * FROM distributors_list WHERE Name_distributor='$name'";
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				$errmsg_arr[] = 'Member Name duplicate!';
				$errflag = true;
			}
			@mysql_free_result($result);
		}
		else {
			die("Query failed");
		}
	}

	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: add_dsitributor.php");
		exit();
	}
	


	//Create INSERT query
	$qry = "INSERT INTO distributors_list(Name_distributor, phone_distributor, address_distributor, distributor_for) VALUES('$name' ,'$phone','$address','$dist_for_series')";
	$result = @mysql_query($qry);

	//Check whether the query was successful or not
	if($result) {
	$_SESSION['register'] = "Distributor successfully added!";	
		header("location: distributors.php?tab=distributors");
		exit();
	}else {
		die("Query failed");
	}
?>