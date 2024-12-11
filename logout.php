<?php
session_start();


	//Unset the variables stored in session
	unset($_SESSION['SESS_MEMBER_ID']);
	unset($_SESSION['SESS_FIRST_NAME']);
	unset($_SESSION['SESS_LAST_NAME']);

$_SESSION['message'] = "You have been successfully loged Out!";
header("location: index.php");
 ?>
