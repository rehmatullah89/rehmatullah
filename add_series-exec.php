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
	$s_name = clean($_POST['series_name']);
	
	if($s_name == '') {
		$errmsg_arr[] = 'Name missing';
		$errflag = true;
	}
	//Check for duplicate login ID
	if($s_name != '') {
		$qry = "SELECT * FROM series WHERE series_name='$s_name'";
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				$errmsg_arr[] = 'Series Name duplicate!';
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
		header("location: add_series.php");
		exit();
	}
	
	//Create INSERT query
	$qry = "INSERT INTO series(series_name) VALUES('$s_name')";
	$result = @mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
	$_SESSION['register'] = 'Series successfully added!';	
		header("location: add_series.php");
		exit();
	}else {
		die("Query failed");
	}
?>