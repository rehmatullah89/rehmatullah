<?phprequire_once('config.php');	//Connect to mysql server	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);	if(!$link) {		die('Failed to connect to server: ' . mysql_error());	}		//Select database	$db = mysql_select_db(DB_DATABASE);	if(!$db) {		die('Unable to select database');	}	$cat = $_REQUEST['cat'];$query = "DELETE FROM series WHERE series_name='$cat'";mysql_query($query);header('Location: view_series.php');?>