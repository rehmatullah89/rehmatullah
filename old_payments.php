<?php
error_reporting(0);
include("header2.php");
?>
<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
				<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2 class="extra">Add Old Payment!</h2>
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
							<form action="" method="post" onsubmit="return confirm('Are you sure you want to Submit?');">							
														
<?php	//Include database connection details
	require_once('config.php');
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	date_default_timezone_set('Asia/Karachi');
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die('Unable to select database');
	}
	$dist_id = $_GET['id'];
	$dist_name="";
		// $q3="SELECT * FROM distributors_list dl,payments_history op where op.distributor_id=dl.id_distributor and dl.id_distributor=$dist_id";
		 $q3="SELECT Name_distributor FROM distributors_list where id_distributor=$dist_id";
	$result = mysql_query($q3);
			while($row1=mysql_fetch_array($result)){
				$dist_name = $row1['Name_distributor'];
			}
			?>
			
			<!-- ////////////// table ////////// -->
														<table class="imagetable" width="980">
<tr>
	<th>Date</th><th>Distributor Name</th><th>Add old Amount</th><th>Payment Reason</th><th>Add Now?</th>
</tr>
<tr><td><?php echo date("Y-m-d");?></td><td><?php echo $dist_name;?></td><td><input type="text" name="a_amt" id="a_amt"></td><td><input type="text" name="a_reason" id="a_reason"></td><td><input type="submit" name="AddNow" value="Add Amount"></td></tr>
</table>

			
			<?php
	if(isset($_POST['AddNow'])){
	$id =0;
	$add_amount = $_POST['a_amt'];
	$add_reason = $_POST['a_reason'];
	date_default_timezone_set('Pakistan/Karachi');	
	$date = date("Y-m-d");
		if($add_amount>0){	
			/* Insert old payment */
			$date = date("Y-m-d");
			$qr = "INSERT INTO payments_history (distributor_id, date_added, total_old_amount,payment_reason) VALUES ('$dist_id','$date','$add_amount','$add_reason')";
			mysql_query($qr);
		}
			header('Location: old_payments.php?id='.$dist_id);
	}
	if(isset($_POST['PayNow']))
	{
		// echo "<pre>"; print_r($_POST);exit;
		$total_unpaid_amount = 0;
		$date = date("Y-m-d H:i:s");
		$id =0;
		$pay_amount = $_POST['p_amt']; 
		$check_no = $_POST['check_no']; 
			if($pay_amount>0){
				$qr2 = "INSERT INTO payment_dates (dist_id, amount_paid, pay_date,check_no) VALUES ('$dist_id','$pay_amount','$date','$check_no')";
				mysql_query($qr2);
			}	
			header('Location: old_payments.php?id='.$dist_id);
			
	}
	
	?>
	

<h2 class="extra">All Bills of this Distributor!</h2>
<?php
$sql_dist_name = "SELECT Name_distributor FROM distributors_list where id_distributor=$dist_id";
$resultq = mysql_query($sql_dist_name);
while($rowq = mysql_fetch_array($resultq))
		$dist_name = $rowq['Name_distributor'];
		
$sqli = "SELECT * FROM distributor_bills where bill_status=0 and distributor_name='$dist_name' ORDER BY date_bill DESC";

	$resulti = mysql_query($sqli);
?>
<table class="imagetable" width="980" align="center">
<tr>
	<th>Index</th><th>Bill Date</th><th>Distributor Name</th><th>Customer Name</th><th>Total Bill</th><th>Print Bill Details</th>
</tr>
	<?php
	$i=1;
	$unpaid =0;
	$t_unpaid =0;
	$total_bills_amount = 0;
	
		while($row = mysql_fetch_array($resulti))
		{
		 $bill_no = $row['bill_no'];
		 $distributor_name = $row['distributor_name'];
		 $customer_name = $row['customer_name'];
		 $total_amount = $row['total_amount'];
		 $total_bills_amount += $row['total_amount'];
		 $amount_paid = $row['amount_paid'];
		 $date_bill = $row['date_bill'];
		 $bill_status = $row['bill_status'];
		 $date = explode(' ',$date_bill);
		 $date_bill = $date[0];
		 $unpaid = $total_amount-$amount_paid;
		 $t_unpaid = $t_unpaid + $unpaid;
		 ?>
	<tr>
	<td><?php echo $i+$start;?></td><td><?php echo $date_bill;?></td><td><?php echo $distributor_name;?></td><td><?php echo $customer_name;?></td><td><?php echo $total_amount;?></td><td><a href="print_bill.php?b_id=<?php echo $bill_no;?>"><input type="button" name="printNow" value="Print Bill"></a></td>
</tr>	 
		 
<?php	
$i++;	 
		}
		
		$q13="SELECT * FROM payments_history where distributor_id=$dist_id";
		$result13 = mysql_query($q13);
		$total_previous_amount = 0;
		while($row=mysql_fetch_array($result13)){
			$total_previous_amount += $row['total_old_amount'];
		}
?>
</table>
</br>
</br>
<h5><a href="javascript:show_popup('my_popup')">History Payments!</a></h5>
<div id="my_popup" style="display:none;border:1px dotted gray;padding:.3em;background-color:white;position:absolute;width:200px;height:200px;left:100px;top:100px">
    <div align="right">
        <a href="javascript:hide_popup('my_popup')">close</a>
    </div>
<h3>All Payments <?php echo $dist_name;?>!</h3>

<table class="imagetable" width="350" align="left">
<tr>
	<th>Index</th><th>Payment Date</th><th>Amount</th><th>Check No.</th>
</tr>
<?php
$sql_p = "SELECT * FROM payment_dates where dist_id=$dist_id ORDER BY pay_date DESC";
$result_p = mysql_query($sql_p);
$jp=1;
$total_amount_paid = 0;
	while($row_p = mysql_fetch_array($result_p))
		{
		 $amount_paid = $row_p['amount_paid'];
		 $total_amount_paid += $row_p['amount_paid']; 
		 $pay_date = $row_p['pay_date'];
		 $check_no = $row_p['check_no'];
		 ?>
		 <tr><td><?php echo $jp++;?></td><td><?php echo date('Y-m-d',strtotime($pay_date));?></td><td><?php echo $amount_paid;?></td><td><?php echo $check_no;?></td></tr>
		 <?php
		}
?>
</table>

</div>
</br>


		<div class="datagrid"><table>
<thead><tr><th></th><th></th></tr></thead>
<tbody>
<tr class="alt"><td>Total Un-paid Amount</td><td><b>RS:&nbsp&nbsp&nbsp&nbsp<?php echo ($total_previous_amount+$total_bills_amount)-$total_amount_paid;?></b></td><td><input type="text" name="p_amt" id="p_amt"></td><td><input type="text" name="check_no" value="check no?"></td><td><input type="submit" name="PayNow"  value="Pay Bill Now!"></td></tr>
</tbody>
</table></div>
</form>
		
</br>
<a href="edit_distributor.php?id=<?php echo $dist_id;?>"><img src="images/pl.jpg"></a>

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
<script language="javascript">
function show_popup(id) {
    if (document.getElementById){
        obj = document.getElementById(id);
        if (obj.style.display == "none") {
            obj.style.display = "";
        }
    }
}
function hide_popup(id){
    if (document.getElementById){
        obj = document.getElementById(id);
        if (obj.style.display == ""){
            obj.style.display = "none";
        }
    }
}
</script>