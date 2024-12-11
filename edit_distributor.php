<?php
require_once('auth.php');
error_reporting(0);
include("header.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
				<div class="inside1">
					<div class="wrap">
					<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
						<?php 
						if(isset($_SESSION['register_message']))
						echo "<h1>".$_SESSION['register_message']."</h1>";
						?>
						<ul class="banners1 wrapper">
						
					<li><a href="add_customers.php?id=<?php echo $_REQUEST['id'];?>">Add Customers</a></li>
					<li><a href="add_discounts.php?id=<?php echo $_REQUEST['id'];?>">Add Discounts</a></li>
					<li><a href="view_customers.php?id=<?php echo $_REQUEST['id'];?>">View Customers</a></li>
					<li><a href="old_payments.php?id=<?php echo $_REQUEST['id'];?>">Payments</a></li>
					</ul>
						<article class="col-2">
							<h2>Update Distributor</h2>
							<form id="contacts-form" action="" method="post">
								<fieldset>
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
		die("Unable to select database");
	}
	$id = $_REQUEST['id'];
	
	if(!empty($_POST))
	{	
		if($_POST['submit'] == 'Delete Distriutor'){
			mysql_query("DELETE FROM payments_history where distributor_id=".$id."");
			mysql_query("DELETE FROM payment_dates where dist_id=".$id."");
			$qry = "SELECT bill_no from distributor_bills where distributor_name='".$_POST['dist_name']."'";
			$bills = mysql_query($qry);
			while($row = mysql_fetch_array($bills)){
				mysql_query("DELETE FROM bill_details where bill_no=".$row['bill_no']."");
			}
			mysql_query("DELETE FROM distributor_bills where distributor_name='".$_POST['dist_name']."'");
			mysql_query("DELETE FROM discounts_distributors where distributor_id=".$id."");
			mysql_query("DELETE FROM customers where distributor_id=".$id."");
			mysql_query("DELETE FROM distributors_list where id_distributor=".$id."");
		}

		$name = $_POST['dist_name'];
		$phone = $_POST['dist_phone'];
		$address = $_POST['dist_address'];
		$q="update distributors_list set Name_distributor='$name', phone_distributor='$phone',address_distributor='$address' where id_distributor='$id'";
		$r=mysql_query($q);
		header("location: distributors.php?tab=distributors");
	}

	
$q="SELECT * FROM distributors_list where id_distributor='$id'";
$r=mysql_query($q);

while($row=mysql_fetch_array($r))
{

	$id = $row['id_distributor']; ?>			
					<div class="field text"><label>Your Name:</label><input name="dist_name" type="text" value="<?php if(isset($row['Name_distributor']))
					echo $row['Name_distributor'];
					?>" readonly></div>
					<div class="field text"><label>Dist. Type:</label><input name="dist_type" type="text" value="<?php if(isset($row['distributor_for']))
					echo ucfirst($row['distributor_for']);
					?>" readonly></div>
					<div class="field text"><label>Your Phone:</label><input name="dist_phone" type="text" value="<?php if(isset($row['phone_distributor']))
					echo $row['phone_distributor'];
					?>"></div>
					<div class="field text"><label>Your Address:</label><input name="dist_address" type="text" value="<?php if(isset($row['address_distributor']))
					echo $row['address_distributor'];
					?>"></div>
									<div class="alignright1">
									<input type="submit" name="submit" class="link2" style="color: white; width:160px !important; border: 1px solid; border-radius: 10px; padding:7px !important;" onclick="return confirm('Are you sure , you want to delete this Distributor!');" value="Delete Distriutor">
									<input type="submit" name="submit" class="link2" style="color: white; width:160px !important; border: 1px solid; border-radius: 10px; padding:7px !important;" value="Update">
									</div>
<?php }?>

									</fieldset>
							</form>
						</article>
						
						
						<div class="clear"></div>
					</div>
				</div>
						</div>
	</div>
</section>
</div>
<?php
include("footer.php");
?>		