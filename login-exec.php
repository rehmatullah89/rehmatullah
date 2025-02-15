<?php
	//Start session
	session_start();
	
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
	
	//Sanitize the POST values
	$email = clean($_POST['email']);
	$password = clean($_POST['Password']);
	
	//Input Validations
	if($email == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$errmsg_arr[] = 'Email Format is Incorrect';
		$errflag = true;
	}
	// echo $email;
	// echo $password;
	// die();
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: index.php");
		exit();
	}
	
	//Create query
	$qry="SELECT * FROM members WHERE member_email_id='$email' AND passwd='".md5($_POST['Password'])."'";
	$result=mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
 		
	if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$members = mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $members['member_email_id'];
			$_SESSION['SESS_FIRST_NAME'] = $members['firstname'];
			$_SESSION['SESS_LAST_NAME'] = $members['lastname'];
			session_write_close();
			header("location: home.php");
			exit();
		}else {
			//Login failed
			echo "<br/><br/>";
			$_SESSION['message'] = "You are not authenticated!";
			header("location: index.php");
			exit();
		}
	}else {
		die("Query failed");
	}
?>