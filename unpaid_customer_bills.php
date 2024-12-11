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
div.pagination {
	padding: 3px;
	margin: 3px;
}

div.pagination a {
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #AAAADD;
	
	text-decoration: none; /* no underline */
	color: green;
}
div.pagination a:hover, div.pagination a:active {
	border: 1px solid green;

	color: #000;
}
div.pagination span.current {
	padding: 2px 5px 2px 5px;
	margin: 2px;
		border: 1px solid green;
		
		font-weight: bold;
		background-color: green;
		color: #FFF;
	}
	div.pagination span.disabled {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #EEE;
	
		color: #DDD;
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
	
	$tbl_name="customers_bills";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 1;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query = "SELECT COUNT(*) as num FROM $tbl_name";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	/* Setup vars for query. */
	$targetpage = "unpaid_customer_bills.php"; 	//your file name  (the name of this file)
	$limit = 10; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT * FROM $tbl_name ORDER BY date_bill DESC LIMIT $start, $limit";
	$result = mysql_query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?page=$prev\">&larr; previous</a>";
		else
			$pagination.= "<span class=\"disabled\">&larr; previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?page=$next\">next &rarr;</a>";
		else
			$pagination.= "<span class=\"disabled\">next &rarr;</span>";
		$pagination.= "</div>\n";		
	}
?>
<table class="imagetable" width="980" align="center">
<tr>
	<th>Bill No.</th><th>Bill Date</th><th>Customer Name</th><th>Total Bill</th><th>Paid Amount</th><th>UnPaid Amount</th><th>Pay Now?</th><th>Delete Bill</th><th>Print Slip</th>
</tr>
	<?php
	$i=1;
		while($row = mysql_fetch_array($result))
		{
		 $bill_no = $row['bill_no'];
		 $distributor_name = $row['distributor_name'];
		 $customer_name = $row['customer_name'];
		 $total_amount = $row['total_amount'];
		 $amount_paid = $row['amount_paid'];
		 $date_bill = $row['date_bill'];
		 $bill_status = $row['bill_status'];
		 $date = explode(' ',$date_bill);
		 $date_bill = $date[0];?>
	<tr>
	<td><?php echo $bill_no;?></td><td><?php echo $date_bill;?></td><td><?php echo $customer_name;?></td><td><?php echo $total_amount;?></td><td><?php echo $amount_paid;?></td><td><?php echo $total_amount-$amount_paid;?></td><td><?php if($bill_status == 1) echo "-";
	else {?><a href="pay_manual_bill.php?b_id=<?php echo $bill_no;?>"><input type="button" name="payNow" value="Pay Now"></a><?php }?></td><td><a onclick="return confirm('Are u sure, you want to delete this bill?');" href="delete_cbill.php?id=<?php echo $bill_no; ?>">Delete Bill</a></td><td><a href="print_manual_bill.php?b_id=<?php echo $bill_no;?>"><input type="button" name="printNow" value="Print Bill"></a></td>
</tr>	 
		 
<?php	
$i++;	 
		}
?>
</table>
</br>

<?=$pagination?>
</br></br></br>
				</div>
		</div>
	</section>
</div>
	<?php
include("footer.php");
?>