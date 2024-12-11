<?php
error_reporting(0);
include("header.php");
?>
<script>
$(document).ready(function () {
    $('#Distributor').change(function(){
        $.ajax({
            url: "get_customers.php",
            type: "post",
            data: {option: $(this).find("option:selected").val()},
            success: function(data){
                //adds the echoed response to our container
                $("#customers").html(data);
            }
        });
    });
$(".show_cust").hide();
$("#distributor_selected").click(function(){
     $(".show_dist").show();
	 $(".show_cust").hide();
 });

 $("#customer_selected").click(function(){
     $(".show_cust").show();
	 $(".show_dist").hide();
 });
 
	});
</script>
<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
	<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside">
			
					<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<h2>Generate Reports here!</h2>
				<div class="inside1">
	Select Distributor Report:<input type="radio" id="distributor_selected" name="person" <?php if (isset($person) && $person=="distributor_selected") echo "checked"; else 
	echo "checked";
	?> value="distributor_selected">
	&nbsp&nbsp&nbsp&nbsp
    Select Customer Report:<input type="radio" id="customer_selected" name="person" <?php if (isset($person) && $person=="customer_selected") echo "checked";?> value="customer_selected"> 
	</br></br></br>
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
	
		
?>
				<form name="tstest" action="reports.php?tab=reports" method="post">
<label>From</label>
<input type="Text" name="timestamp" value="">
<a href="javascript:show_calendar('document.tstest.timestamp', document.tstest.timestamp.value);"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp"></a>
<label>To</label>
<input type="Text" name="timestamp1" value="">
<a href="javascript:show_calendar('document.tstest.timestamp1', document.tstest.timestamp1.value);"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp"></a>&nbsp&nbsp&nbsp
</br></br>
<div class="show_dist">
<label>Distributor:&nbsp</label><select name="Distributor" id="Distributor"><option>Select Distributor</option>
	<?php
		$Name_distributor = "";
		$qry = "SELECT Name_distributor from distributors_list";
		$dist_names=mysql_query($qry);
		while($row2=mysql_fetch_array($dist_names)){
			$Name_distributor = $row2['Name_distributor'];
			echo "<option>".$row2['Name_distributor']."</option>";
		}
		?>
</select>
<label>Customer:&nbsp</label> <select id="customers" name="customers" >
			<!-- the customers options will be added here -->
		</select>
</div> 
<div class="show_cust">
<label>Customer Name:</label><input type="Text" name="customer_name_rep" value="">
</div> 		
</br>
</br>
<input type="submit" name="submit" value="Generate Report">
</form>
<div class="bot-indent" id="printableArea">

		<?php 
		if(isset($_POST['Distributor'])){
			$dist_n = $_POST['Distributor'];
			echo "<h3  style='color:red;'>Distributor Name:&nbsp&nbsp&nbsp $dist_n</h3>";
		}
		?>	
			<!--//////////////Report View/////////////////// -->
<br/>
<table class="imagetable" width="920" align="center">
<tr>
	<th>Bill No.</th><th>Bill Date</th><th>Customer Name</th><th>Amount Paid (Debit)</th><th>Pay Detail</th><th>Bill Amount(Credit)</th>
	<?php  
		if (isset($_POST['Distributor']) && isset($_POST['customers'])) {
			echo "<th>Balance</th>";
		}
	?>
</tr>
<?php 
if(isset($_POST['submit'])){
 /*&& $_POST['Distributor'] != ''*/
 date_default_timezone_set('Asia/Karachi');
   if($_POST['timestamp'] != '' && $_POST['timestamp1'] != ''){
		$distributor_name = "";
		//$date_from = $_POST['timestamp'];
		//$date_from= date ("Y-m-d",  strtotime($date_from));
		$date_from = "1970-01-01";
		
		//$date_till = $_POST['timestamp1'];
		//$date_till= date ("Y-m-d", strtotime($date_till));
		$date_till= date("Y-m-d");
		
		$Distributor = "";
		$dist_type = "";
		if(isset($_POST['Distributor'])){
			$Distributor = $_POST['Distributor'];
			$dist_type = mysql_result(mysql_query('SELECT distributor_for from distributors_list where Name_distributor="'.$Distributor.'"'), 0);
		}
		
		$customer = "";
		if(isset($_POST['customers']))
		$customer = $_POST['customers'];
		
		$customer_rep = "";
		if(isset($_POST['customer_name_rep']))
		$customer_rep = $_POST['customer_name_rep'];
		$q3 = "";
		if($Distributor != "" && $Distributor != 'Select Distributor')
		{	if($customer == ""){
				$q3="SELECT * FROM distributor_bills where distributor_name='$Distributor' and ( DATE_FORMAT(date_bill,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER by date_bill ASC";
			
				}
			else{
				$q3="SELECT * FROM distributor_bills where distributor_name='$Distributor' and customer_name='$customer' and ( DATE_FORMAT(date_bill,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER by date_bill ASC";
				}
		}
		else if(!empty($customer_rep)) 
		{
			$q3="SELECT * FROM customers_bills where customer_name='$customer_rep' and ( DATE_FORMAT(date_bill,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER by date_bill ASC";
		}
		$result1 = 0;
		$print_bill_array = 0;
		if($q3 != ""){
			$result = mysql_query($q3);
			$i=1;
			$pp = 0;
			$customer_payment_arr= array();
			$my2darray = array();
			$print_bill_array = array();
			while($row=mysql_fetch_array($result)){
					if($customer_rep == "")	
						$distributor_name = $row['distributor_name'];
				if ($dist_type == 'sanva') {
					$bill_no = $row['sbill_no'];
				}else if ($dist_type == 'dash') {
					$bill_no = $row['dbill_no'];
				}else		
					$bill_no = $row['bill_no'];
				$customer_name = $row['customer_name'];
				$total_amount = $row['total_amount'];
				$date_bill = $row['date_bill'];
				$amount_paid1 = $row['amount_paid'];
				$amount_balance = $total_amount-$amount_paid1;
				$my2darray = array($amount_paid1,$amount_balance);
				$customer_payment_arr[$pp] = $my2darray;
				$print_bill_array[$pp] = array('bill_no' => $bill_no,'bill_date' => date('Y-m-d',strtotime($date_bill)),'customer_name' => $customer_name,'bill_amount' =>  $total_amount);
				$pp++;
				$i++;
			}
			/////////////get paid amount history--------
				$dist_id = "";
				if($_POST['timestamp'] != '' && $_POST['timestamp1'] != '' && $_POST['Distributor'] != '' && isset($_POST['customers']) ){
					// print_r($_POST['submit']);exit;
					$qry = "SELECT id_distributor,Name_distributor from distributors_list where Name_distributor='$Distributor'";
					$dist_qry=mysql_query($qry);
					while($row2=mysql_fetch_array($dist_qry)){
						$dist_id = $row2['id_distributor'];
					}
					$sql_p = "SELECT * FROM payment_dates where dist_id=$dist_id and ( DATE_FORMAT(pay_date,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER BY pay_date ASC";
					$result_p = mysql_query($sql_p);
					$pay_date = "";
					$amount_paid ="";
					$check_no ="";
					$payment_dates_array = array();
					$j=0;
					while($row_p = mysql_fetch_array($result_p)){
						 $amount_paid = $row_p['amount_paid'];
						 $pay_date = $row_p['pay_date'];
						 $check_no = $row_p['check_no'];
						 $payment_dates_array[$j] = array('paid_amount' => $amount_paid,'date_paid_amount' =>  date('Y-m-d',strtotime($pay_date)),'check_no'=>$check_no);
						 $j++;
					}
					/*echo "<pre>";
					print_r($payment_dates_array);
					die();*/
				}
				/////////// get old payments data
				$entry_point=0;
				$old_payments = @mysql_query("SELECT * from payments_history where distributor_id='".$dist_id."'");
				$result1 = mysql_fetch_object($old_payments);
				$entry_point = count($result1);
				//print("entry_point=".$entry_point."<br/>");
		}		
	}
	 else
		echo "<h3 style='color:red;'>Please Fill the Required Information in above fields!</h3>";

//echo "<pre>";print_r($payment_dates_array);

/*Print Bill*/
$ikp=0;
$jkp=0;
$sum_of_totals = 0;
		
	if (count($result1)>0) {
			$sum_of_totals = $result1->total_old_amount;
			$start = strtotime(date("Y-m-d", strtotime($print_bill_array[$ikp]['bill_date'])));
			$end = strtotime(date("Y-m-d", strtotime($payment_dates_array[$jkp]['date_paid_amount'])));
			echo "<tr><td><b>Old Amount<b/></td><td>".$result1->date_added."</td><td>-</td><td>-</td>".((isset($_POST['Distributor']) && isset($_POST['customers']))?'<td>-</td>':'')."<td></td><td>".$result1->total_old_amount."</td></tr>";
			if (empty($print_bill_array)) {
				while ($jkp<count($payment_dates_array)) {
					$sum_of_totals = $sum_of_totals - $payment_dates_array[$jkp]['paid_amount'];
				 	echo "<tr><td>-</td><td>".$payment_dates_array[$jkp]['date_paid_amount']."</td><td>-</td><td>".$payment_dates_array[$jkp]['paid_amount']."</td><td>".$payment_dates_array[$jkp]['check_no']."</td><td>-</td><td>".$sum_of_totals."</td></tr>";
					$jkp++;
				}
			}
			else{
				while ($start>$end && $jkp<count($payment_dates_array)) {
					$end = strtotime(date("Y-m-d", strtotime($payment_dates_array[$jkp]['date_paid_amount'])));
					$sum_of_totals = $sum_of_totals - $payment_dates_array[$jkp]['paid_amount'];
				 	echo "<tr><td>-</td><td>".$payment_dates_array[$jkp]['date_paid_amount']."</td><td>-</td><td>".$payment_dates_array[$jkp]['paid_amount']."</td><td>".$payment_dates_array[$jkp]['check_no']."</td><td>-</td><td>".$sum_of_totals."</td></tr>";
					$jkp++;
				}
			}
	}//endif check point

while ($ikp<count($print_bill_array)) {
	$sum_of_totals = $sum_of_totals + $print_bill_array[$ikp]['bill_amount'];
?>
<tr>
	<td><?php echo $print_bill_array[$ikp]['bill_no'];?></td><td><?php echo $print_bill_array[$ikp]['bill_date'];?></td><td><?php echo $print_bill_array[$ikp]['customer_name'];?></td><td>-</td><td>-</td><td><?php echo $print_bill_array[$ikp]['bill_amount'];?></td>
<?php  
	if (isset($_POST['Distributor']) && isset($_POST['customers'])) {
		echo "<td>".$sum_of_totals."</td>";
	}
?>
</tr>
<?php
if (isset($_POST['Distributor']) && isset($_POST['customers']) && !empty($payment_dates_array[$jkp])) {
		$start = strtotime(date("Y-m-d", strtotime($print_bill_array[$ikp+1]['bill_date'])));
		$end = strtotime(date("Y-m-d", strtotime($payment_dates_array[$jkp]['date_paid_amount'])));
			if ($end >= $start) {
				while ($end < strtotime(date("Y-m-d", strtotime($print_bill_array[$ikp+1]['bill_date']))) || $jkp<count($payment_dates_array)) {
					$end = strtotime(date("Y-m-d", strtotime($payment_dates_array[$jkp]['date_paid_amount'])));
					$sum_of_totals = $sum_of_totals - $payment_dates_array[$jkp]['paid_amount'];
				 	echo "<tr><td>-</td><td>".$payment_dates_array[$jkp]['date_paid_amount']."</td><td>-</td><td>".$payment_dates_array[$jkp]['paid_amount']."</td><td>".$payment_dates_array[$jkp]['check_no']."</td><td>-</td><td>".$sum_of_totals."</td></tr>";	
					$jkp++;
					if ($end >=strtotime(date("Y-m-d", strtotime($print_bill_array[$ikp+1]['bill_date']))) || $jkp==count($payment_dates_array)) {
						break;
					}
				}
			}
			if ($ikp == count($print_bill_array)) {
				while ($jkp<count($payment_dates_array)) {
					$sum_of_totals = $sum_of_totals - $payment_dates_array[$jkp]['paid_amount'];
				 	echo "<tr><td>-</td><td>".$payment_dates_array[$jkp]['date_paid_amount']."</td><td>-</td><td>".$payment_dates_array[$jkp]['paid_amount']."</td><td>".$payment_dates_array[$jkp]['check_no']."</td><td>-</td><td>".$sum_of_totals."</td></tr>";	
					$jkp++;
				}	
			}
}
	$ikp++;
}

	/*	echo "<pre>";print_r($payment_dates_array);
 echo "<hr/><pre>";print_r($print_bill_array);		*/
echo "</table><tr/>";
}// is isset submit ends here

?>
			<!--//////////////////////////////// -->
	</table></div>	
<?php
if (isset($_POST['submit'])) {
?>
	<input type="button" onclick="printDiv('printableArea')" value="Print Bill!" />	
<?php
}
?>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>