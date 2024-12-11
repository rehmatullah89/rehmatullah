
<?php	//Include database connection details
	error_reporting(0);
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
	
	
	// echo "<pre>";print_r($_POST);

		$j=1;
		$arr = array();
		$arr2 = array();
		foreach ($_POST as $key=>$value){
		
			if($key == 'distributor' )
			$Distributor_id = $value;
			
			
			 if($value=='submit')
				echo "";
			else
			{ 
				// echo $key."&".$value;
				if($key != 'distributor' ){
				$cat_item = explode("^", $key);
				$arr[$j] = $cat_item;
				$arr2[$j] = $value;
				$j++;
				}
			}
		}
		// echo "<pre>";print_r($arr);
	
$l=0;
$cat = 0;
$item= 0;
$num_rows =0;
$total_amount = 0;
		foreach($arr as $arr_vals){
			$k=0;
			$l++;
			foreach($arr_vals as $key1=>$val1){
				if($k==0)
				 $cat = $val1;
				 if($k == 1){
				  $item= $val1;
					}
				$k++;
				
			}
			
			$series = str_replace('_', ' ', $cat);
			$item_name = str_replace('_', ' ', $item);
			$discount = $arr2[$l];
			
			if($l == 1){
			$q = "SELECT * from discounts_distributors where distributor_id='$Distributor_id'";
			$result = mysql_query($q);
			$num_rows = mysql_num_rows($result);
			 }
			 
			if($num_rows > 0){
			
			$q2="SELECT item_name, series_name,distributor_id from discounts_distributors where distributor_id=$Distributor_id and series_name='$series' and item_name='$item_name'";
			$r2=mysql_query($q2);
			$num_r2 = mysql_num_rows($r2);
			
			if($num_r2 > 0)			
			$qr = "UPDATE discounts_distributors SET discount=$discount where distributor_id=$Distributor_id and series_name='$series' and item_name='$item_name'";
			else 
			$qr = "INSERT INTO discounts_distributors (series_name, item_name, discount, distributor_id) VALUES ('$series','$item_name','$discount','$Distributor_id')";
			
			mysql_query($qr);
			}
			else
			{
			$qry = "INSERT INTO discounts_distributors (series_name, item_name, discount, distributor_id) VALUES ('$series','$item_name','$discount','$Distributor_id')";
			mysql_query($qry);
			}
		}


header("Location: distributors.php?tab=distributors");
?>	