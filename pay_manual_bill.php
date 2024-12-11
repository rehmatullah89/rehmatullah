<?php
error_reporting(0);
include("header2.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
				<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2 class="extra">Pay this Bill?</h2>
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
							<form action="" method="post" onsubmit="return confirm('Are you sure you want to Pay this Order?');">							
														<!-------------- table-------------->
														<table class="imagetable" width="980">
<tr>
	<th>Bill No.</th><th>Customer Name</th><th>Total Amount</th><th>Write check no.(if any)</th><th>Enter Paid Amount(PKR)</th><th>Pay</th>
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
	
	if(isset($_POST['PayNow'])){
	// echo "<pre>";print_r($_POST);
	$bill_no = $_REQUEST['b_id'];
	$check_no = $_POST['check_no'];
	$pmt_amt = $_POST['p_amt'];
	$amount_paid  =0;
	$check_no_status =0;
	$total_amount =0;
	$q3="SELECT * FROM customers_bills where bill_no=$bill_no";
	$result = mysql_query($q3);
			while($row=mysql_fetch_array($result)){
				$total_amount = $row['total_amount'];
				$check_no_status = $row['payment_method'];
				$amount_paid = $row['amount_paid'];
			
			}
			if($check_no == '' OR $check_no == 0)
			$check_no = $check_no_status;
		
		$amount_paid = $pmt_amt + $amount_paid;		
		$amount_un_paid = $total_amount - $amount_paid;
		if ($amount_un_paid <= 0 )
		{
		$qu="UPDATE customers_bills SET amount_paid=$amount_paid, payment_method='$check_no',bill_status=1 where bill_no=$bill_no";
		mysql_query($qu);
		header('Location: pay_bill_status.php');
		}
		else
		{
		$qu="UPDATE customers_bills SET amount_paid=$amount_paid, payment_method='$check_no' where bill_no=$bill_no";
		mysql_query($qu);
		header('Location: pay_bill_status.php');
		}
	
	}
		
	$bill_no = $_REQUEST['b_id'];
	$q3="SELECT * FROM customers_bills where bill_no='$bill_no'";
			$result = mysql_query($q3);
			while($row=mysql_fetch_array($result)){ ?>
			
<tr><td><?php echo $_REQUEST['b_id'];?></td><td><?php echo $row['customer_name']; ?></td><td><?php echo $row['total_amount']; ?></td><td><input type="text" name="check_no" id="check_no"></td><td><input type="text" name="p_amt" id="p_amt"></td><td><input type="submit" name="PayNow" value="Pay Amount Now"></td></tr>
			<?php			}
?>

</table>
</form>
</br>
<a href="unpaid_bills.php"><img src="images/pl.jpg"></a>

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