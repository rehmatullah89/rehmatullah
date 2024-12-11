<?php
require_once('auth.php');
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
<title>Sanva.com</title>
<meta name="description" content="Place your description here">
<meta name="keywords" content="put, your, keyword, here">
<meta name="author" content="Templates.com - website templates provider">
<meta charset="utf-8">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<script type="text/javascript" src="js/maxheight.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_300.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="js/jquery.faded.js"></script>
<script type="text/javascript" src="js/jquery.jqtransform.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript">
	$(function(){
		$("#faded").faded({
			speed: 500,
			crossfade: true,
			autoplay: 10000,
			autopagination:false
		});
		
		$('#domain-form').jqTransform({imgPath:'jqtransformplugin/img/'});
	});
</script>
<!--[if lt IE 7]>
<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
<style type="text/css">
table.imagetable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 2px;
	border-color: #999999;
	border-collapse: collapse;
}
table.imagetable th {
	background:#b5cfd2 url('images/cell-blue.jpg');
	border-width: 2px;
	padding: 5px;
	border-style: solid;
	border-color: #999999;
}
table.imagetable td {
	background:#dcddc0 url('images/cell-grey.jpg');
	border-width: 1px;
	border-width: 1px;
	padding: 10px;
	border-style: solid;
	border-color: #999999;
}
</style>
</head>
<body id="page1" onLoad="new ElementMaxHeight();">
<div class="tail-top">
<!-- header -->
	<header>
		<div class="container">
			<div class="header-box">
				<div class="left">
					<div class="right">
						<nav>
							<ul>
								<li <?php if ($_GET['tab'] == 'home') echo 'class="current"'; ?>><a href="home.php?tab=home">Home</a></li>
							<li <?php if ($_GET['tab'] == 'distributors') echo 'class="current"'; ?>><a href="distributors.php?tab=distributors">Distributors</a></li>
							<li <?php if ($_GET['tab'] == 'products') echo 'class="current"'; ?>><a href="products.php?tab=products">Products</a></li>
							<li <?php if ($_GET['tab'] == 'reports') echo 'class="current"'; ?>><a href="reports.php?tab=reports">Reports</a></li>
							<li <?php if ($_GET['tab'] == 'orders') echo 'class="current"'; ?>><a href="orders.php?tab=orders">Orders</a></li>
							<li <?php if ($_GET['tab'] == 'emails') echo 'class="current"'; ?>><a href="emails.php?tab=emails">Emails</a></li>
							</ul>
						</nav>
						<h1><a href="index.php"><span>Sanva</span><font style="color: red;">KEC</font>.com</a></h1>
					</div>
				</div>
			</div>
			<span class="top-info">Welcome <b><?php echo $_SESSION['SESS_FIRST_NAME']." ".$_SESSION['SESS_LAST_NAME'];?></b></span>
			
		</div>
	</header>
<!-- content -->
	<section id="content">
		<div class="container">

			<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
			<div class="inside">
			
						<h2 align="left" style="color:green;">Unpaid Bills...</h2>
				
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
	
?>
				
				
<table class="imagetable" width="980" align="center">
<tr>
	<th>Index</th><th>Bill Date</th><th>Distributor Name</th><th>Customer Name</th><th>Total Bill</th><th>Paid Amount</th><th>UnPaid Amount</th><th>Pay Now?</th><th>Print Slip</th>
</tr>
<?php
$qr="SELECT * FROM distributor_bills where bill_status=0 ORDER BY date_bill DESC";
$bill_info=mysql_query($qr);
$i=0;
while($row=mysql_fetch_array($bill_info)){
 $bill_no = $row['bill_no'];
 $distributor_name = $row['distributor_name'];
 $customer_name = $row['customer_name'];
 $total_amount = $row['total_amount'];
 $amount_paid = $row['amount_paid'];
 $date_bill = $row['date_bill'];
 $date = explode(' ',$date_bill);
 $date_bill = $date[0];
 
?>

<tr>
	<td><?php echo $i;?></td><td><?php echo $date_bill;?></td><td><?php echo $distributor_name;?></td><td><?php echo $customer_name;?></td><td><?php echo $total_amount;?></td><td><?php echo $amount_paid;?></td><td><?php echo $total_amount-$amount_paid;?></td><td><a href="pay_bill.php?b_id=<?php echo $bill_no;?>"><input type="button" name="payNow" value="Pay Now"></a></td><td><a href="print_bill.php?b_id=<?php echo $bill_no;?>"><input type="button" name="printNow" value="Print Bill"></a></td>
</tr>
<?php
$i++;
}
?>
</table>
</br></br></br></br>
				
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>