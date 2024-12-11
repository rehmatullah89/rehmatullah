<?php
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
	
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$new = str_replace('%20',' ',$actual_link);

	preg_match('~item=(.*?)&~', $new, $output);
	$item = $output[1];
	$cat = $_REQUEST['cat'];

$query = "DELETE FROM products WHERE item_name='$item' and series_name='$cat'";
mysql_query($query);
header('Location: category_products.php?cat='.$cat);

?>