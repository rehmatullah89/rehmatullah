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

$('.confirm').live('click', function(){
    return confirm("Are you sure you want to Delete this Item?");
});
</script>
<!--[if lt IE 7]>
<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
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
			<div class="inside">
				
		
				<div class="inside1">
					<div class="wrap">
							<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
							<div id="registration" style="width:800px; margin:0 auto;">
 <h2><?php if(isset($_REQUEST['cat'])) 
 echo $s_name = $_REQUEST['cat'];
 ?></h2>

</div>
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
	
$q2="SELECT * FROM products where series_name='$s_name'";
$r2=mysql_query($q2);
while($row=mysql_fetch_array($r2)){
 // $s_name = $row1['series_name'];
		if($i>4)
		  $i=1;
				?>
					<div class="box col-<?php echo $i;?> " >
						<div class="border-right maxheight">
							<div class="border-bot maxheight">
								<div class="border-left maxheight">
									<div class="left-top-corner maxheight">
										<div class="right-top-corner maxheight">
											<div class="right-bot-corner maxheight">
												<div class="left-bot-corner maxheight">
													<div class="inner">
														<h3><?php echo $row['item_name'];?></h3>
														<ul class="info-list">
															
															<?php
															echo "<li><img border=\"0\" src=\"".$row['item_image']."\" width=\"120\" alt=\"".$row['item_name']."\" height=\"80\"></li>";
															?>
															
														<li>Cottons:<?php echo $row['cotton_quantity'];?></li>															
														<li>Boxes:<?php echo $row['remaining_box_qty'];?></li>															
														<li>Items: <?php echo $row['remaining_item_qty'];?></li>

														<li> <b>Delete Item:</b> <a href="delete_item.php?item=<?php  echo  htmlspecialchars($row['item_name']);?>&cat=<?php 
														echo $s_name;?>" class="confirm">Delete</a></li>	
														
														</ul>
														<span class="price">Rps. <?php echo $row['unit_price'];?> p/u</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php }?>	
					</div>
			
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
