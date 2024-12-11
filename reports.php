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
	<th>Bill No.</th><th>Bill Date</th><th>Customer Name</th><th>Amount Paid (Debit)</th><?php  
		if (isset($_POST['Distributor']) && isset($_POST['customers'])) {
			echo "<th>Pay Detail</th>";
		}else
			echo "<th>Phone/Addresss</th>";
	?><th>Bill Amount(Credit)</th>
	<?php  
		if (isset($_POST['Distributor']) && isset($_POST['customers'])) {
			echo "<th>Balance</th>";
		}
	?>
</tr>
<?php 
$final_arr = array();
if(isset($_POST['submit'])){
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
			$q3="SELECT * FROM customers_bills where customer_name LIKE '$customer_rep' OR customer_phone LIKE '$customer_rep' and ( DATE_FORMAT(date_bill,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER by date_bill ASC";
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
			$customer_bill_array = array();
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
				if (!empty($customer_rep)){
					$customer_bill_array[] = array('bill_no' => $bill_no,'bill_date' => date('Y-m-d',strtotime($date_bill)),'customer_name' => $customer_name,'paid_amount'=>$row['amount_paid'],'phone'=>$row['customer_phone'],'address'=>$row['customer_address'],'bill_amount' =>$row['total_amount']);
				}
				$print_bill_array[$pp] = array('bill_no' => $bill_no,'bill_date' => date('Y-m-d',strtotime($date_bill)),'customer_name' => $customer_name,'paid_amount'=>'-','check_no'=>'-','bill_amount' =>  $total_amount,'checkPoint'=>0);
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
						 $payment_dates_array[$j] = array('bill_no' => '-','bill_date' => date('Y-m-d',strtotime($pay_date)),'customer_name' => '-','paid_amount'=>$amount_paid,'check_no'=>$check_no,'bill_amount' =>'-','checkPoint'=>1);

						 $j++;
					}
					//echo "<pre>"; //
					$final_arr = array_merge($print_bill_array, $payment_dates_array);
					usort($final_arr, make_comparer('bill_date','bill_no', SORT_ASC));
					//print_r($final_arr);
					//die();
				}
				else if (isset($_POST['customer_name_rep'])) {
					foreach ($customer_bill_array as $key => $value) {
						echo "<tr><td>".$value['bill_no']."</td><td>".$value['bill_date']."</td><td>".$value['customer_name']."</td><td>".$value['paid_amount']."</td><td>".$value['phone'].'('.$value['address'].")</td><td>".$value['bill_amount']."</td></tr>";
					}
				}

				/////////// get old payments data
				$entry_point=0;
				$old_payments = @mysql_query("SELECT * from payments_history where distributor_id='".$dist_id."'");
				$result1 = mysql_num_rows($old_payments);
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
$balance_total = 0;
		
	if (!empty($final_arr)){
		while($row = mysql_fetch_array($old_payments)){
			echo "<tr><td><b>Old/Other Amount<b/></td><td>".$row['date_added']."</td><td>-</td><td>-</td>".((isset($_POST['Distributor']) && isset($_POST['customers']))?'<td>'.$row['payment_reason'].'</td>':'')."<td></td><td>".$row['total_old_amount']."</td></tr>";
			$balance_total += $row['total_old_amount'];
		}
		foreach ($final_arr as $key => $value) {
			if ($value['checkPoint'] == 0)
				$balance_total = $balance_total + $value['bill_amount'];
			else if ($value['checkPoint'] == 1)
				$balance_total = $balance_total - $value['paid_amount'];
			echo '<tr><td>'.$value['bill_no'].'</td><td>'.$value['bill_date'].'</td><td>'.$value['customer_name'].'</td><td>'.$value['paid_amount'].'</td><td>'.$value['check_no'].'</td><td>'.$value['bill_amount'].'</td><td>'.$balance_total.'</td></tr>';
		}
	}//endif check point



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
function make_comparer() {
    // Normalize criteria up front so that the comparer finds everything tidy
    $criteria = func_get_args();
    foreach ($criteria as $index => $criterion) {
        $criteria[$index] = is_array($criterion)
            ? array_pad($criterion, 3, null)
            : array($criterion, SORT_ASC, null);
    }

    return function($first, $second) use (&$criteria) {
        foreach ($criteria as $criterion) {
            // How will we compare this round?
            list($column, $sortOrder, $projection) = $criterion;
            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

            // If a projection was defined project the values now
            if ($projection) {
                $lhs = call_user_func($projection, $first[$column]);
                $rhs = call_user_func($projection, $second[$column]);
            }
            else {
                $lhs = $first[$column];
                $rhs = $second[$column];
            }

            // Do the actual comparison; do not return if equal
            if ($lhs < $rhs) {
                return -1 * $sortOrder;
            }
            else if ($lhs > $rhs) {
                return 1 * $sortOrder;
            }
        }

        return 0; // tiebreakers exhausted, so $first == $second
    };
}
include("footer.php");
?>