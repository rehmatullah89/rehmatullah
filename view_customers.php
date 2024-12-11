<?php
error_reporting(0);
include("header2.php");
?>
<script>
$('.confirm').live('click', function(){
    return confirm("Are you sure you want to Delete this Person?");
});
</script>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
				
				<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2 class="extra">Customers List</h2>
				<div class="box extra">
					<div class="border-right">
						<div class="border-bot">
							<div class="border-left">
								<div class="left-top-corner1">
									<div class="right-top-corner1">
										<div class="right-bot-corner">
											<div class="left-bot-corner">
												<div class="inner">
											
													<div class="border-top">
														<div class="inner1">
															
														<!-------------- table-------------->
														<table class="imagetable" width="980">
<tr>
	<th>Index</th><th>Name</th><th>Phone</th><th>Address</th><th>Delete</th>
</tr>
				<?php	//Include database connection details
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
	
	$distributor_id = $_REQUEST['id'];
	$index_id =1;
	$qry = "SELECT * FROM customers where distributor_id='$distributor_id'";
		$res=mysql_query($qry);
		while($row=mysql_fetch_array($res)){
			$customer_name = $row['customer_name'];
			$customer_phone = $row['customer_phone'];
			$customer_address = $row['customer_address'];
			
					
?>
<tr>
	<td><?php echo $index_id;?></td><td><?php echo $customer_name;?></td><td><?php echo $customer_phone;?></td><td><?php echo $customer_address;?></td><td><a href="delete_customer.php?dist=<?php  echo $distributor_id;?>&cust=<?php echo $customer_name;?>" class="confirm">Delete</a></td>
</tr>
<?php
$index_id++;
}
?>
</table>

														</div>
														
													</div>
												</br></br></br></br></br></br></br></br></br>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>		