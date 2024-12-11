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
		</br>		
		</br>
		<?php 
		if(isset($_POST['Distributor'])){
			$dist_n = $_POST['Distributor'];
			echo "<h3  style='color:red;'>Distributor Name:&nbsp&nbsp&nbsp $dist_n</h3>";
		}
		?>	
		<div class="inside bot-indent" id="printableArea">
			<!--//////////////Report View/////////////////// -->
<table class="imagetable" width="650" align="center">
<tr>
	<th>Bill No.</th><th>Bill Date</th><th>Customer Name</th><th>Bill Amount(Credit)</th><th>Amount Paid (Debit)</th><th>Balance</th>
</tr>
<?php 
if(isset($_POST['submit'])){
   if($_POST['timestamp'] != '' && $_POST['timestamp1'] != '' && $_POST['Distributor'] != ''){
		$distributor_name = "";
		$date_from = $_POST['timestamp'];
		$date_from= date ("Y-m-d",  strtotime($date_from));
		
		$date_till = $_POST['timestamp1'];
		$date_till= date ("Y-m-d", strtotime($date_till));
		
		if(isset($_POST['Distributor']))
		$Distributor = $_POST['Distributor'];
		
		if(isset($_POST['customers']))
		$customer = $_POST['customers'];
		
		$customer_rep = "";
		if(isset($_POST['customer_name_rep']))
		$customer_rep = $_POST['customer_name_rep'];
		
		if($customer_rep == "")
		{
			if($customer == ""){
				$q3="SELECT * FROM distributor_bills where distributor_name='$Distributor' and ( DATE_FORMAT(date_bill,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER by date_bill DESC";
			
				}
			else{
				$q3="SELECT * FROM distributor_bills where distributor_name='$Distributor' and customer_name='$customer' and ( DATE_FORMAT(date_bill,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER by date_bill DESC";
				}
		}
		else
		{
			$q3="SELECT * FROM customers_bills where customer_name='$customer_rep' and ( DATE_FORMAT(date_bill,'%Y-%m-%d') BETWEEN '$date_from' and '$date_till') ORDER by date_bill DESC";
		}
			$result = mysql_query($q3);
			$i=1;
			$pp = 0;
			$customer_payment_arr= array();
			$my2darray = array();
			$print_bill_array = array();
			while($row=mysql_fetch_array($result)){
					if($customer_rep == "")	
						$distributor_name = $row['distributor_name'];
				$bill_no = $row['bill_no'];
				$customer_name = $row['customer_name'];
				$total_amount = $row['total_amount'];
				$date_bill = $row['date_bill'];
				$amount_paid1 = $row['amount_paid'];
				$amount_balance = $total_amount-$amount_paid1;
				$my2darray = array($amount_paid1,$amount_balance);
				$customer_payment_arr[$pp] = $my2darray;
				$print_bill_array[$bill_no] = array('bill_date' => $date_bill,'customer_name' => $customer_name,'bill_amount' =>  $total_amount);
				$pp++;
				$i++;
			}
			/////////////get paid amount history--------
				$dist_id = "";
				if($_POST['timestamp'] != '' && $_POST['timestamp1'] != '' && $_POST['Distributor'] != '' && (isset($_POST['customers']))){
					// print_r($_POST['submit']);exit;
					$qry = "SELECT id_distributor,Name_distributor from distributors_list where Name_distributor='$Distributor'";
					$dist_qry=mysql_query($qry);
					while($row2=mysql_fetch_array($dist_qry)){
						$dist_id = $row2['id_distributor'];
					}
					$sql_p = "SELECT * FROM payment_dates where dist_id=$dist_id ORDER BY pay_date DESC";
					$result_p = mysql_query($sql_p);
					$pay_date = "";
					$amount_paid ="";
					$check_no ="";
					$payment_dates_array = array();
					while($row_p = mysql_fetch_array($result_p)){
						 $amount_paid = $row_p['amount_paid'];
						 $pay_date = $row_p['pay_date'];
						 $check_no = $row_p['check_no'];
						 $payment_dates_array[$pay_date] = $amount_paid;
					}
				}
	}
		else
			echo "<h3 style='color:red;'>Please Fill the Required Information in above fields!</h3>";
}// is isset submit ends here

?>
			<!--//////////////////////////////// -->
		</div>	<input type="button" onclick="printDiv('printableArea')" value="Print Bill!" />	
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>