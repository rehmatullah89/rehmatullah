<?php
error_reporting(0);
include("header2.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
				
<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4><h2 class="extra">List of Distributors</h2><ul class="banners wrapper">
					<li><a href="reverse_bills.php">Reverse Invoice</a></li>
					</ul>
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
															<div class="">
															
															</div>
														</div>
													</div>
													<div class="border-top">
														<div class="inner1">
	<h4 class="extra aligncenter">
	
	<a href="add_dsitributor.php" style="color:red;">Create New Distributor...</a>
	
	</h4>
															<table class="imagetable" width="980">
<tr>
	<th>Name</th><th>Phone</th><th>Address</th><th>Customers</th><th>Dist. Info..</th>
</tr>
																
																				
																<?php 
	//Include database connection details
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
	
$q="SELECT * FROM distributors_list ORDER BY Name_distributor ASC";
$r=mysql_query($q);

while($row=mysql_fetch_array($r))
{

	$id = $row['id_distributor'];
	
echo "<tr><td>".$row['Name_distributor']."</td><td>".$row['phone_distributor']."</td><td>".$row['address_distributor']."</td>";
?>
<?php
echo "<td>";
	$q1="SELECT customer_name FROM customers where distributor_id=$id ORDER BY customer_name ASC";
											$r1=mysql_query($q1);
											echo "<select>";
											while($row1=mysql_fetch_array($r1))
											echo "<option>".$row1['customer_name']."</option>";		
											echo "</select>";


?>
</td><td>
<a href="edit_distributor.php?id=<?php echo $id;?>">Details</a></td></tr>
											<?php }?>		
</table>											
																				<div class="clear"></div>
																			
																
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
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>		