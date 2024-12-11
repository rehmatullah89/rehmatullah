 <?php
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
	
			$s_name = $_REQUEST['s_n'];
			
 $q2="SELECT item_name,unit_price,cotton_quantity,cotton_boxses,per_box_items,series_name,item_image,remaining_box_qty FROM products where series_name='$s_name' AND (cotton_quantity>0 OR remaining_box_qty>0)";

$j =0;
$r2=mysql_query($q2);
$num_rows = mysql_num_rows($r2);
$div_res = $num_rows/2;

	// while($row=mysql_fetch_array($r2))
	// {					
		// $item_name = $row['item_name'];
		// $image = $row['item_image'];
		
	// }

$i=1;
echo "<table><tr>";
while($j < 2){		
$j++;
echo "<td><table class='imagetable' align='center'><tr><th>Serial#</th><th>Item Model</th><th>Box Qty</th><th>Item Qty</th></tr>";
	
	while($row=mysql_fetch_array($r2))
	{					
		$item_name = $row['item_name'];
		$image = $row['item_image'];
		// $per_box_items = $row['per_box_items'];
		// $amount = $row['unit_price'];
	
	echo "<tr><td>$i</td><td>";
	//echo "<img border=\"0\" src=\"".$row['item_image']."\" width=\"50\" alt=\"".$row['item_name']."\" height=\"35\">";
	echo trim($row['item_name']);
	echo "</td><td><input type='text' size='4' style='height:25px;' name='";
	echo trim($s_name).'^'.trim($item_name);
	echo "' id='";
	echo trim($s_name).'^'.trim($item_name);
	echo "' onchange='myFunction(this.id, this.value)'></td>";
	echo "<td><input type='text' size='4' style='height:25px;' name='";
	echo trim($s_name).'#'.trim($item_name);
	echo "' id='";
	echo trim($s_name).'#'.trim($item_name);
	echo "' onchange='myFunction(this.id, this.value)'></td></tr>";
	
	$i++;
	if($i == $div_res+1)
	break;
	
	
	}
echo "</table></td>";
}
echo "</tr></table>";
?>											
											
