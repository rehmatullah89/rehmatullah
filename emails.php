<?php
include("header2.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
				
				<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2 class="extra">Customers Emails</h2>
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
	<th>Index</th><th>Name</th><th>Phone</th><th>Email</th><th>Message</th>
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
	
	$qry = "SELECT * FROM (SELECT * from emails_leads ORDER BY index_id DESC LIMIT 50) sub ORDER BY index_id ASC";
		$emails=mysql_query($qry);
		while($row=mysql_fetch_array($emails)){
			$index_id = $row['index_id'];
			$person_name = $row['person_name'];
			$person_phone = $row['person_phone'];
			$person_email = $row['person_email'];
			$person_msg = $row['person_msg'];
			$msg = substr($person_msg, 0, 50);
					
?>
<tr>
	<td><?php echo $index_id;?></td><td><?php echo $person_name;?></td><td><?php echo $person_phone;?></td><td><?php echo $person_email;?></td><td><a href="#" alt="<?php echo $person_msg;?>"><?php echo $msg;
			echo "...";?></a></td>
</tr>
<?php

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