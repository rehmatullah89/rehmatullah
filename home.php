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
	padding: 8px;
	border-style: solid;
	border-color: #999999;
}
table.imagetable td {
	background:#dcddc0 url('images/cell-grey.jpg');
	border-width: 1px;
	border-width: 1px;
	padding: 12px;
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
						<h1><a href="index.php"><span>Sanva</span><font style="color:red; !important">KEC</font>.com</a></h1>
					</div>
				</div>
			</div>
			<span class="top-info">Welcome <b><?php echo $_SESSION['SESS_FIRST_NAME']." ".$_SESSION['SESS_LAST_NAME'];?></b></span>			<h4 align="right" style="margin-top:-70px;"><a href="change_password.php" style="color:red;">[Change Password]</a></h4>
			
		</div>
	</header>
<!-- content -->
	<section id="content">
		<div class="container">
<ul class="banners wrapper">
					<li><a href="unpaid_bills.php">Distributor Bills</a></li>
					<li><a href="reverse_bills_list.php">Reverse Bills</a></li>
					<li><a href="unpaid_customer_bills.php">Customer Bills</a></li>
					<li><a href="update_inventory.php">Update Inventory</a></li>
					</ul>
			<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
			<div class="inside">
			
						<h2 align="left" style="color:green;">Remaining Inventory!</h2>
				<div class="wrapper row-1">
				
				<?php 
				$i=1;

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
	
$q="SELECT series_name FROM series";
$series_names=mysql_query($q);

while($row1=mysql_fetch_array($series_names)){
 $s_name = $row1['series_name'];
$q2="SELECT * FROM products where series_name='$s_name'";


$r2=mysql_query($q2);


	// $id = $row['id_distributor'];
		if($i>4)
		  $i=1;
				?>				
				<div class="box col-<?php echo $i;?> ">
						<div class="border-right maxheight">
							<div class="border-bot maxheight">
								<div class="border-left maxheight">
									<div class="left-top-corner maxheight">
										<div class="right-top-corner maxheight">
											<div class="right-bot-corner maxheight">
												<div class="left-bot-corner maxheight">
													<div class="inner">
														<h3><?php echo $s_name?></h3>
														<ul class="info-list">
															<li><span><b>Item name</b></span><b>Qunatity</b>&nbsp&nbsp Update?</li>
																														<?php
															while($row=mysql_fetch_array($r2))
															{
																echo "<li><span>".$row['item_name']."</span>".$row['cotton_quantity'].".".$row['remaining_box_qty']." cottons";
																
																?>
																&nbsp&nbsp <a href='update_product.php?id=<?php echo $row['item_id'];?>'>Update</a>
																<?php
																echo "</li>";
															}
															?>
														</ul>
														<div class="aligncenter"><a href="category_products.php?cat=<?php echo $row1['series_name'];?>" class="link1"><span><span>Learn More</span></span></a></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php 
				$i++;
				}
				?>
			</div>
			
			</div>
		</div>
	</section>
</div>
<?php
include("footer.php");
?>