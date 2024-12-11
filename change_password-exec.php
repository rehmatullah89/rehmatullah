<?php
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
		// print_r($_POST);
		// die("bye");
	//Sanitize the POST values
	$old_pass = clean($_POST['old_pass']);
	$new_pass = clean($_POST['new_pass']);
	$new_pass2 = clean($_POST['new_pass2']);
	
	$user1_old_pass = clean($_POST['user1_old_pass']);
	$user1_new_pass = clean($_POST['user1_new_pass']);
	$user1_new_pass2 = clean($_POST['user1_new_pass2']);
	
	$user2_old_pass = clean($_POST['user2_old_pass']);
	$user2_new_pass = clean($_POST['user2_new_pass']);
	$user2_new_pass2 = clean($_POST['user2_new_pass2']);
	
	//Check for duplicate login ID
	if($old_pass != '' && $new_pass!='' && $new_pass2!='') {
		$qry = "SELECT * FROM members WHERE member_email_id='".$_SESSION['SESS_MEMBER_ID']."' AND passwd='".md5($old_pass)."'";
		$result = mysql_query($qry);
		if(!$result) {
				$errmsg_arr[] = 'member does not exist';
				$errflag = true;
		}else{
			if(!$errflag){
					//Create INSERT query
					$qry = "UPDATE members SET passwd='".md5($new_pass)."' WHERE member_email_id='".$_SESSION['SESS_MEMBER_ID']."'";
					$result = @mysql_query($qry);
					//Check whether the query was successful or not
					if($result) {
						unset($_SESSION['SESS_MEMBER_ID']);
						unset($_SESSION['SESS_FIRST_NAME']);
						unset($_SESSION['SESS_LAST_NAME']);
						$_SESSION['message'] = "Password changed successfully!";
						header("location: index.php");
					}else {
						die("Query failed");
					}	
			}else{
				$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
				session_write_close();
				header("location: change_password.php");
				exit();
			}
		}
	}
	if($user1_old_pass != '' && $user1_new_pass!='' && $user1_new_pass2!='' && $user1_new_pass == $user1_new_pass2){
		//Create INSERT query
		$qry = "UPDATE members SET passwd='".md5($user1_new_pass)."' WHERE member_email_id='sanva_user@sanvakec.com'";
		$result = @mysql_query($qry);
		//Check whether the query was successful or not
		if($result){
			$_SESSION['message'] = "Sanva User Password changed successfully!";
			header("location: change_password.php");
		}else{
				$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
				session_write_close();
				header("location: change_password.php");
				exit();
		}
		
	}
	if($user2_old_pass != '' && $user2_new_pass!='' && $user2_new_pass2!='' && $user2_new_pass == $user2_new_pass2){
		//Create INSERT query
		$qry = "UPDATE members SET passwd='".md5($user2_new_pass)."' WHERE member_email_id='dash_user@sanvakec.com'";
		$result = @mysql_query($qry);
		//Check whether the query was successful or not
		if($result){
			$_SESSION['message'] = "Dash User Password changed successfully!";
			header("location: change_password.php");
		}else{
				$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
				session_write_close();
				header("location: change_password.php");
				exit();
		}
		
	}
	

?>